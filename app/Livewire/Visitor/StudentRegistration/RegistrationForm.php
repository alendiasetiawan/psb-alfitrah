<?php

namespace App\Livewire\Visitor\StudentRegistration;

use App\Models\User;
use App\Enums\RoleEnum;
use Livewire\Component;
use App\Models\Core\Branch;
use App\Helpers\MessageHelper;
use Livewire\Attributes\Title;
use App\Queries\Core\UserQuery;
use App\Helpers\AdmissionHelper;
use App\Helpers\WhaCenterHelper;
use App\Traits\StudentQuotaTrait;
use Illuminate\Support\Facades\DB;
use App\Helpers\CodeGeneratorHelper;
use Illuminate\Support\Facades\Hash;
use App\Models\AdmissionData\Student;
use Illuminate\Support\Facades\Crypt;
use App\Queries\Core\AdmissionFeeQuery;
use App\Queries\Core\EducationProgramQuery;
use App\Models\AdmissionData\RegistrationPayment;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\MultiStudent;
use App\Models\AdmissionData\ParentModel;

#[Title('Formulir Pendaftaran Siswa Baru')]
class RegistrationForm extends Component
{
    use StudentQuotaTrait;

    //Boolean
    public $isAdmissionOpen = true, $isQuotaAvailable = true;
    //String
    public $selectedBranchName, $selectedProgramName, $alertResentOtp = '', $admissionName;
    //Integer
    public $realBranchId;
    //Array
    public $inputs = [
        'studentName' => '',
        'gender' => '',
        'selectedBranchId' => '',
        'selectedEducationProgramId' => '',
        'countryCode' => 62,
        'mobilePhone' => '',
        'password' => '',
        'activeAdmissionId' => '',
        'activeAdmissionBatchId' => '',
        'registrationFee' => '',
        'otp'
    ];
    public $branchLists = [], $educationProgramLists = [];

    protected $rules = [
        'inputs.studentName' => [
            'required',
            'min:3'
        ],
        'inputs.gender' => 'required',
        'inputs.selectedBranchId' => 'required',
        'inputs.selectedEducationProgramId' => 'required',
        'inputs.mobilePhone' => [
            'required',
            'min:7',
            'max:12'
        ],
        'inputs.password' => [
            'required',
            'min:6'
        ],
    ];
    protected $messages = [
        'inputs.studentName.required' => 'Nama harus diisi',
        'inputs.studentName.min' => 'Nama minimal :min karakter',
        'inputs.gender.required' => 'Jenis kelamin harus diisi',
        'inputs.selectedBranchId.required' => 'Pilih cabang',
        'inputs.selectedEducationProgramId.required' => 'Pilih program pendidikan',
        'inputs.mobilePhone.required' => 'Nomor HP harus diisi',
        'inputs.mobilePhone.min' => 'Nomor HP minimal :min angka',
        'inputs.mobilePhone.max' => 'Nomor HP maksimal :max angka',
        'inputs.mobilePhone.numeric' => 'Nomor HP harus angka',
        'inputs.password.required' => 'Password harus diisi',
        'inputs.password.min' => 'Password minimal 6 karakter',
    ];

    //HOOK - Execute once when component is rendered
    public function mount($branchId)
    {
        $activeAdmission = AdmissionHelper::activeAdmission();

        $this->branchLists = Branch::pluck('name', 'id');
        $this->inputs['activeAdmissionId'] = $activeAdmission->id;
        $this->admissionName = $activeAdmission->name;
        $this->inputs['activeAdmissionBatchId'] = AdmissionHelper::activeAdmissionBatch($this->inputs['activeAdmissionId'])->id;
        $this->isAdmissionOpen = AdmissionHelper::isAdmissionOpen();

        try {
            $realBranchId = Crypt::decrypt($branchId);
            $findBranch = Branch::find($realBranchId);

            $this->inputs['selectedBranchId'] = $findBranch ? $realBranchId : '';
            $this->selectedBranchName = $findBranch ? $findBranch->name : '';
            $this->educationProgramLists = $findBranch ? EducationProgramQuery::getProgramInBranch($realBranchId)->toArray() : [];
        } catch (\Throwable $th) {
            session()->flash('error-id', 'Dilarang modifikasi ID parameter');
        }
    }

    //HOOK - Execute when property is updated
    public function updated($propertyName)
    {
        if ($propertyName == 'inputs.selectedBranchId') {
            $this->educationProgramLists = EducationProgramQuery::getProgramInBranch($this->inputs['selectedBranchId'])->toArray();
        }

        if ($propertyName == 'inputs.selectedEducationProgramId') {
            //Determine branch and program
            $findProgram = EducationProgramQuery::fetchDetailProgram($this->inputs['selectedEducationProgramId']);
            $this->selectedBranchName = $findProgram->branch->name;
            $this->selectedProgramName = $findProgram->name;

            //Determine registration fee
            $findFee = AdmissionFeeQuery::fetchFeePerProgram($this->inputs['activeAdmissionId'], $this->inputs['selectedEducationProgramId']);
            $this->inputs['registrationFee'] = $findFee->registration_fee;

            //Check if program is available
            $this->isQuotaAvailable = $this->isQuotaAvailable($this->inputs['activeAdmissionId'], $this->inputs['selectedEducationProgramId']);
        }

        if ($propertyName == "inputs.mobilePhone") {
            $userRule = [
                'inputs.mobilePhone' => 'unique:users,username'
            ];

            $userMessage = [
                'inputs.mobilePhone.unique' => 'Nomor HP ini sudah terdaftar'
            ];

            $this->validate($userRule, $userMessage);
        }
    }

    //ACTION - Save student data
    public function saveStudentRegistration()
    {
        $this->validate($this->rules, $this->messages);

        //Check if student whatsapp number is active
        $testMessage = MessageHelper::waCheckNumberMessage();
        $waNumber = $this->inputs['countryCode'] . $this->inputs['mobilePhone'];
        $sendTestMessage = WhaCenterHelper::sendText($waNumber, $testMessage);
        $responseTest = json_decode($sendTestMessage, true);
        if ($responseTest['status'] == false) {
            return session()->flash('save-failed', 'Maaf, kami tidak dapat mengirimkan pesan. Harap mencantumkan nomor Whatsapp yang aktif!');
        }

        try {
            //Set OTP for verification
            $otp = CodeGeneratorHelper::otpCode();
            $otpExpired = CodeGeneratorHelper::otpExpiredOnMinute();

            //Generate reg number
            $regNumber = CodeGeneratorHelper::studentRegNumber($this->admissionName, $this->inputs['activeAdmissionBatchId']);

            //Query to database
            DB::transaction(function () use ($otp, $otpExpired, $waNumber, $regNumber) {
                //Insert user data
                $createUser = User::create([
                    'role_id' => RoleEnum::STUDENT,
                    'username' =>  $this->inputs['mobilePhone'],
                    'password' => Hash::make($this->inputs['password']),
                    'fullname' =>  $this->inputs['studentName'],
                    'gender' =>  $this->inputs['gender'],
                    'otp' =>  $otp,
                    'otp_expired_at' => $otpExpired,
                    'is_verified' => 0
                ]);

                //Insert parent data
                $createParent = ParentModel::create([
                    'user_id' => $createUser->id,
                ]);

                //Insert student data
                $createStudent = Student::create([
                    'parent_id' => $createParent->id,
                    'branch_id' => $this->inputs['selectedBranchId'],
                    'education_program_id' => $this->inputs['selectedEducationProgramId'],
                    'admission_id' => $this->inputs['activeAdmissionId'],
                    'admission_batch_id' => $this->inputs['activeAdmissionBatchId'],
                    'reg_number' => $regNumber,
                    'name' => $this->inputs['studentName'],
                    'gender' => $this->inputs['gender'],
                    'country_code' => $this->inputs['countryCode'],
                    'mobile_phone' => $this->inputs['mobilePhone']
                ]);

                //Insert multi student data
                MultiStudent::create([
                    'parent_id' => $createParent->id,
                    'student_id' => $createStudent->id
                ]);

                //Insert registration payment data
                RegistrationPayment::create([
                    'student_id' => $createStudent->id,
                    'amount' => $this->inputs['registrationFee']
                ]);

                //Insert admission verification data
                AdmissionVerification::create([
                    'student_id' => $createStudent->id,
                ]);

                //Send OTP via Whatsapp to student's number
                $successMessage = MessageHelper::waRegistrationSuccess($otp, $this->selectedBranchName, $this->inputs['studentName'], $this->selectedProgramName);
                WhaCenterHelper::sendText($waNumber, $successMessage);

                session()->flash('registration-success');
            });
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('save-failed', 'Pendaftaran gagal, silahkan coba lagi!');
        }
    }

    //ACTION - OTP verification after user successfully registered
    public function otpVerification()
    {
        $this->validate([
            'inputs.otp' => 'required'
        ], [
            'inputs.otp.required' => 'Kode OTP harus diisi!'
        ]);

        //Check if OTP is valid and not verified
        $isOtpValid = UserQuery::isOtpValid($this->inputs['otp']);
        if (!$isOtpValid) {
            return session()->flash('otp-failed', 'Kode OTP salah, silahkan coba lagi!');
        }

        //Check if OTP is expired
        $isOtpExpired = UserQuery::isOtpExpired($this->inputs['otp']);
        if ($isOtpExpired) {
            return session()->flash('otp-failed', 'Kode OTP sudah tidak berlaku, silahkan request lagi!');
        }

        try {
            //Update student's verification status
            $findUserOtp = UserQuery::findUserOtp($this->inputs['otp']);
            $findUserOtp->update([
                'is_verified' => true,
                'verified_at' => now()
            ]);

            session()->flash('otp-success', 'Verifikasi akun berhasil, silahkan login!');
            $this->redirect(route('login'));
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('otp-failed', 'Ups... Terjadi kesalahan, silahkan coba lagi');
        }
    }

    //ACTION - Resend OTP when user click resend button
    public function resendOtp()
    {
        try {
            User::where('username', $this->inputs['mobilePhone'])
                ->update([
                    'otp' => CodeGeneratorHelper::otpCode(),
                    'otp_expired_at' => CodeGeneratorHelper::otpExpiredOnMinute()
                ]);

            $otp = CodeGeneratorHelper::otpCode();
            $waNumber = $this->inputs['countryCode'] . $this->inputs['mobilePhone'];

            //Resend OTP to student
            $resendOtpMessage = MessageHelper::waResendOtp($otp);
            WhaCenterHelper::sendText($waNumber, $resendOtpMessage);

            session()->flash('registration-success');
            session()->flash('otp-success', 'Kode telah dikirim, silahkan cek aplikasi Whatsapp anda!');
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('otp-failed', 'Ups... Terjadi kesalahan, silahkan coba lagi');
        }
    }

    public function render()
    {
        return view('livewire.web.visitor.student-registration.registration-form')->layout('components.layouts.web.web-blank-header');
    }
}
