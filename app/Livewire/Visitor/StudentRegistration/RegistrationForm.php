<?php

namespace App\Livewire\Visitor\StudentRegistration;

use App\Enums\RoleEnum;
use App\Helpers\AdmissionHelper;
use App\Helpers\CodeGeneratorHelper;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\MultiStudent;
use App\Models\AdmissionData\ParentModel;
use App\Models\AdmissionData\RegistrationPayment;
use App\Models\AdmissionData\Student;
use App\Models\User;
use App\Queries\Core\AdmissionFeeQuery;
use App\Queries\Core\BranchQuery;
use App\Queries\Core\EducationProgramQuery;
use App\Traits\StudentQuotaTrait;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Formulir Pendaftaran Siswa Baru')]
class RegistrationForm extends Component
{
    use StudentQuotaTrait;

    // Boolean
    public bool $isAdmissionOpen = false;

    public bool $isQuotaAvailable = false;

    #[Locked]
    public bool $registrationCompleted = false;

    public $hasInvalidBranchLink = false;

    #[Locked]
    public ?int $registeredUserId = null;

    public string $registrationUnavailableMessage = '';

    public string $programUnavailableMessage = '';

    // String
    public string $selectedBranchName = '';

    public string $selectedProgramName = '';

    public string $alertResentOtp = '';

    public string $admissionName = '';

    // Integer
    public $realBranchId;

    // Array
    public array $inputs = [
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
        'otp' => '',
    ];

    #[Locked]
    public array $branchLists = [];

    public $educationProgramLists = [];

    protected $rules = [
        'inputs.studentName' => [
            'required',
            'string',
            'min:3',
        ],
        'inputs.gender' => 'required|in:Laki-Laki,Perempuan',
        'inputs.selectedBranchId' => 'required',
        'inputs.selectedEducationProgramId' => 'required',
        'inputs.mobilePhone' => [
            'required',
            'min:7',
            'max:12',
        ],
        'inputs.password' => [
            'required',
            'string',
            'min:6',
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

    // HOOK - Execute once when component is rendered
    public function mount(string $branchId): void
    {
        try {
            $realBranchId = Crypt::decrypt($branchId);
        } catch (DecryptException $exception) {
            $this->hasInvalidBranchLink = true;
            $this->registrationUnavailableMessage = 'Tautan pendaftaran tidak valid. Silakan pilih pondok melalui halaman kuota pendaftaran.';

            return;
        }

        if ((! is_int($realBranchId) && ! is_string($realBranchId))
            || filter_var($realBranchId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            $this->hasInvalidBranchLink = true;
            $this->registrationUnavailableMessage = 'Tautan pendaftaran tidak valid. Silakan pilih pondok melalui halaman kuota pendaftaran.';

            return;
        }

        if (! $this->refreshRegistrationContext()) {
            return;
        }

        if ((int) $realBranchId !== 0) {
            if (! array_key_exists((int) $realBranchId, $this->branchLists)) {
                $this->registrationUnavailableMessage = 'Pondok, jenjang pendidikan, atau kuota pada tautan ini belum tersedia. Silakan pilih pondok melalui halaman kuota pendaftaran.';

                return;
            }

            $this->inputs['selectedBranchId'] = (int) $realBranchId;
            $this->loadBranchPrograms();
        }
    }

    protected function refreshRegistrationContext(): bool
    {
        $this->registrationUnavailableMessage = '';
        $this->isAdmissionOpen = false;
        $this->inputs['activeAdmissionId'] = '';
        $this->inputs['activeAdmissionBatchId'] = '';
        $this->inputs['registrationFee'] = '';

        $activeAdmission = AdmissionHelper::activeAdmission();
        if (! $activeAdmission || ! filled($activeAdmission->name)) {
            $this->registrationUnavailableMessage = 'Informasi penerimaan santri baru belum tersedia. Silakan kembali lagi nanti.';

            return false;
        }

        $this->inputs['activeAdmissionId'] = $activeAdmission->id;
        $this->admissionName = $activeAdmission->name;
        $batch = AdmissionHelper::activeAdmissionBatch($activeAdmission->id);
        if (! $batch || ! filled($batch->open_date) || ! filled($batch->close_date)) {
            $this->registrationUnavailableMessage = 'Gelombang pendaftaran belum tersedia. Silakan kembali lagi nanti.';

            return false;
        }

        $this->inputs['activeAdmissionBatchId'] = $batch->id;
        $today = now()->toDateString();
        $this->isAdmissionOpen = $activeAdmission->status === 'Buka'
            && $batch->open_date <= $today && $batch->close_date > $today;
        if (! $this->isAdmissionOpen) {
            return false;
        }

        $this->branchLists = BranchQuery::pluckRegistrationBranches($activeAdmission->id)->toArray();
        if ($this->branchLists === []) {
            $this->registrationUnavailableMessage = 'Informasi pondok, jenjang pendidikan, atau kuota pendaftaran belum tersedia. Silakan kembali lagi nanti.';

            return false;
        }

        return true;
    }

    protected function loadBranchPrograms(): void
    {
        $this->inputs['selectedEducationProgramId'] = '';
        $this->inputs['registrationFee'] = '';
        $this->selectedProgramName = '';
        $this->selectedBranchName = '';
        $this->isQuotaAvailable = false;
        $this->programUnavailableMessage = '';
        $this->educationProgramLists = [];
        $this->resetValidation('inputs.selectedEducationProgramId');

        $branchId = filter_var($this->inputs['selectedBranchId'], FILTER_VALIDATE_INT);
        if (! $branchId || ! array_key_exists($branchId, $this->branchLists)) {
            $this->inputs['selectedBranchId'] = '';

            return;
        }

        $this->selectedBranchName = $this->branchLists[$branchId];
        $this->educationProgramLists = EducationProgramQuery::getProgramInBranch($branchId, (int) $this->inputs['activeAdmissionId'])->toArray();
        if ($this->educationProgramLists === []) {
            $this->programUnavailableMessage = 'Jenjang pendidikan atau kuota penerimaan belum tersedia untuk pondok ini.';
        }
    }

    protected function checkSelectedProgram(): void
    {
        $this->inputs['registrationFee'] = '';
        $this->selectedProgramName = '';
        $this->isQuotaAvailable = false;
        $this->programUnavailableMessage = '';

        $programId = filter_var($this->inputs['selectedEducationProgramId'], FILTER_VALIDATE_INT);
        if (! $programId || $programId < 1) {
            if ($this->inputs['selectedEducationProgramId'] !== '') {
                $this->inputs['selectedEducationProgramId'] = '';
                $this->programUnavailableMessage = 'Program pendidikan tidak tersedia untuk pondok yang dipilih.';
            }

            return;
        }

        $program = EducationProgramQuery::fetchDetailProgram($programId);
        if (! $program || ! filled($program->name) || ! $program->branch || ! filled($program->branch->name) || $program->branch->trashed()
            || (int) $program->branch_id !== (int) $this->inputs['selectedBranchId']) {
            $this->inputs['selectedEducationProgramId'] = '';
            $this->programUnavailableMessage = 'Program pendidikan tidak tersedia untuk pondok yang dipilih.';

            return;
        }

        $this->selectedProgramName = $program->name;
        $this->selectedBranchName = $program->branch->name;
        $admissionId = (int) $this->inputs['activeAdmissionId'];
        if (! $this->programQuota($admissionId, $programId)) {
            $this->programUnavailableMessage = 'Kuota penerimaan belum tersedia untuk program ini.';

            return;
        }

        $fee = AdmissionFeeQuery::fetchFeePerProgram($admissionId, $programId);
        if (! $fee || $fee->registration_fee === null) {
            $this->programUnavailableMessage = 'Biaya pendaftaran belum tersedia untuk program ini. Silakan pilih program lain atau kembali lagi nanti.';

            return;
        }

        $this->inputs['registrationFee'] = $fee->registration_fee;
        $this->isQuotaAvailable = $this->isQuotaAvailable($admissionId, $programId);
    }

    // HOOK - Execute when property is updated
    public function updated(string $propertyName): void
    {
        if ($propertyName == 'inputs.selectedBranchId') {
            $this->loadBranchPrograms();
        }

        if ($propertyName == 'inputs.selectedEducationProgramId') {
            $this->checkSelectedProgram();
        }

        if ($propertyName == 'inputs.mobilePhone') {
            $userRule = [
                'inputs.mobilePhone' => 'unique:users,username',
            ];

            $userMessage = [
                'inputs.mobilePhone.unique' => 'Nomor HP ini sudah terdaftar',
            ];

            $this->validate($userRule, $userMessage);
        }
    }

    // ACTION - Save student data
    public function saveStudentRegistration()
    {
        if ($this->registrationCompleted || $this->hasInvalidBranchLink) {
            return;
        }

        if (! $this->refreshRegistrationContext()) {
            return session()->flash('save-failed', $this->registrationUnavailableMessage ?: 'Pendaftaran sudah tutup. Silakan kembali lagi nanti.');
        }

        $rules = $this->rules;
        $rules['inputs.selectedBranchId'] = ['required', 'integer', Rule::in(array_keys($this->branchLists))];
        $rules['inputs.selectedEducationProgramId'] = ['required', 'integer'];
        $rules['inputs.mobilePhone'][] = 'regex:/^[0-9]{7,12}$/';
        $rules['inputs.mobilePhone'][] = Rule::unique('users', 'username');
        $rules['inputs.countryCode'] = ['required', Rule::in([62])];
        $this->validate($rules, $this->messages + ['inputs.mobilePhone.unique' => 'Nomor HP ini sudah terdaftar']);

        $this->checkSelectedProgram();
        if (! $this->isQuotaAvailable) {
            return session()->flash('save-failed', $this->programUnavailableMessage ?: 'Kuota program yang dipilih sudah tutup atau penuh. Silakan pilih program lain.');
        }

        try {
            $waNumber = $this->inputs['countryCode'].$this->inputs['mobilePhone'];
            $responseTest = json_decode((string) WhaCenterHelper::sendText($waNumber, MessageHelper::waCheckNumberMessage()), true);
            if (($responseTest['status'] ?? false) !== true) {
                return session()->flash('save-failed', 'Maaf, kami tidak dapat mengirimkan pesan. Harap mencantumkan nomor Whatsapp yang aktif!');
            }

            // Set OTP for verification
            $otp = CodeGeneratorHelper::otpCode();
            $otpExpired = CodeGeneratorHelper::otpExpiredOnMinute();

            // Generate reg number
            $regNumber = CodeGeneratorHelper::studentRegNumber($this->admissionName, $this->inputs['activeAdmissionId']);

            // Query to database
            $registeredUserId = DB::transaction(function () use ($otp, $otpExpired, $waNumber, $regNumber) {
                // Insert user data
                $createUser = User::create([
                    'role_id' => RoleEnum::STUDENT,
                    'username' => $this->inputs['mobilePhone'],
                    'password' => Hash::make($this->inputs['password']),
                    'fullname' => $this->inputs['studentName'],
                    'gender' => $this->inputs['gender'],
                    'mobile_phone' => $this->inputs['countryCode'].$this->inputs['mobilePhone'],
                    'otp' => $otp,
                    'otp_expired_at' => $otpExpired,
                    'is_verified' => 0,
                ]);

                // Insert parent data
                $createParent = ParentModel::create([
                    'user_id' => $createUser->id,
                ]);

                // Insert student data
                $createStudent = Student::create([
                    'user_id' => $createUser->id,
                    'parent_id' => $createParent->id,
                    'branch_id' => $this->inputs['selectedBranchId'],
                    'education_program_id' => $this->inputs['selectedEducationProgramId'],
                    'admission_id' => $this->inputs['activeAdmissionId'],
                    'admission_batch_id' => $this->inputs['activeAdmissionBatchId'],
                    'reg_number' => $regNumber,
                    'name' => $this->inputs['studentName'],
                    'gender' => $this->inputs['gender'],
                    'country_code' => $this->inputs['countryCode'],
                    'mobile_phone' => $this->inputs['mobilePhone'],
                ]);

                // Insert multi student data
                MultiStudent::create([
                    'user_id' => $createUser->id,
                    'parent_id' => $createParent->id,
                    'student_id' => $createStudent->id,
                ]);

                // Insert registration payment data
                RegistrationPayment::create([
                    'student_id' => $createStudent->id,
                    'amount' => $this->inputs['registrationFee'],
                ]);

                // Insert admission verification data
                AdmissionVerification::create([
                    'student_id' => $createStudent->id,
                ]);

                // Send OTP via Whatsapp to student's number
                $successMessage = MessageHelper::waRegistrationSuccess($otp, $this->selectedBranchName, $this->inputs['studentName'], $this->selectedProgramName);
                $response = json_decode((string) WhaCenterHelper::sendText($waNumber, $successMessage), true);
                if (($response['status'] ?? false) !== true) {
                    throw new \RuntimeException('Registration OTP delivery failed.');
                }

                return $createUser->id;
            });

            $this->registeredUserId = $registeredUserId;
            $this->registrationCompleted = true;
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('save-failed', 'Pendaftaran gagal, silahkan coba lagi!');
        }
    }

    // ACTION - OTP verification after user successfully registered
    public function otpVerification()
    {
        $this->validate([
            'inputs.otp' => 'required|digits:6',
        ], [
            'inputs.otp.required' => 'Kode OTP harus diisi!',
        ]);

        try {
            $findUserOtp = $this->registrationCompleted && $this->registeredUserId
                ? User::whereKey($this->registeredUserId)->where('otp', $this->inputs['otp'])->where('is_verified', false)->first()
                : null;
            if (! $findUserOtp) {
                return session()->flash('otp-failed', 'Kode OTP salah, silahkan coba lagi!');
            }

            if (! $findUserOtp->otp_expired_at || now()->gte($findUserOtp->otp_expired_at)) {
                return session()->flash('otp-failed', 'Kode OTP sudah tidak berlaku, silahkan request lagi!');
            }

            $findUserOtp->update([
                'is_verified' => true,
                'verified_at' => now(),
            ]);

            session()->flash('otp-success', 'Verifikasi akun berhasil, silahkan login!');
            $this->redirect(route('login'));
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('otp-failed', 'Ups... Terjadi kesalahan, silahkan coba lagi');
        }
    }

    // ACTION - Resend OTP when user click resend button
    public function resendOtp()
    {
        try {
            $user = $this->registrationCompleted && $this->registeredUserId
                ? User::whereKey($this->registeredUserId)->where('is_verified', false)->first()
                : null;
            if (! $user) {
                return session()->flash('otp-failed', 'Data pendaftaran belum tersedia. Silakan daftar terlebih dahulu.');
            }

            $otp = CodeGeneratorHelper::otpCode();
            DB::transaction(function () use ($user, $otp): void {
                $user->update(['otp' => $otp, 'otp_expired_at' => CodeGeneratorHelper::otpExpiredOnMinute()]);

                $response = json_decode((string) WhaCenterHelper::sendText($user->mobile_phone, MessageHelper::waResendOtp($otp)), true);
                if (($response['status'] ?? false) !== true) {
                    throw new \RuntimeException('Registration OTP resend failed.');
                }
            });

            session()->flash('otp-success', 'Kode telah dikirim, silahkan cek aplikasi Whatsapp anda!');
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('otp-failed', 'Ups... Terjadi kesalahan, silahkan coba lagi');
        }
    }

    public function render(): View
    {
        return view('livewire.web.visitor.student-registration.registration-form')->layout('components.layouts.web.web-blank-header');
    }
}
