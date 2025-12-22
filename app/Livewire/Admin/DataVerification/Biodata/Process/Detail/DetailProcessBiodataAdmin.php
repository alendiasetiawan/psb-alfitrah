<?php

namespace App\Livewire\Admin\DataVerification\Biodata\Process\Detail;

use App\Enums\VerificationStatusEnum;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\AdmissionData\AdmissionVerification;
use App\Queries\AdmissionData\StudentQuery;
use App\Traits\FlushStudentAdmissionDataTrait;
use Carbon\Carbon;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Biodata Santri')]
class DetailProcessBiodataAdmin extends Component
{
    use FlushStudentAdmissionDataTrait;

    #[Locked]
    public $decryptedStudentId;
    public bool $isMobile = false;
    public string $studentId = '';
    public object $studentDetail;
    public string $studentName = '', $branchName = '', $programName = '', $academicYear = '';
    public array $inputs = [
        'biodataStatus' => '',
        'invalidReason' => ''
    ];

    //ANCHOR: Boot execuite every time component rendered
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR: Mount execute once
    public function mount()
    {
        $realId = Crypt::decrypt($this->studentId);
        $this->decryptedStudentId = $realId;
        $this->studentDetail = StudentQuery::fetchStudentDetailWithStatus($realId);

        //Set predefine value
        $this->inputs['biodataStatus'] = $this->studentDetail->biodata != VerificationStatusEnum::PROCESS ? $this->studentDetail->biodata : '';
        $this->inputs['invalidReason'] = $this->studentDetail->biodata_error_msg;

        $this->studentName = $this->studentDetail->student_name;
        $this->branchName = $this->studentDetail->branch_name;
        $this->programName = $this->studentDetail->program_name;
        $this->academicYear = $this->studentDetail->academic_year;
    }


    //ANCHOR: Save status biodata
    public function updateBiodataStatus()
    {
        //Validate if status is Invalid
        if ($this->inputs['biodataStatus'] == VerificationStatusEnum::INVALID) {
            $this->validate([
                'inputs.invalidReason' => 'required'
            ], [
                'inputs.invalidReason.required' => 'Alasan tidak boleh kosong'
            ]);
        }

        //Query to database
        try {
            $admission = AdmissionVerification::where('student_id', $this->decryptedStudentId)->first();
            $admission->update([
                'biodata' => $this->inputs['biodataStatus'],
                'biodata_error_msg' => $this->inputs['invalidReason'],
                'biodata_verified_at' => Carbon::now()
            ]);

            $this->flushBiodata($this->decryptedStudentId);
            $this->dispatch('toast', type: 'success', message: 'Status biodata berhasil diubah');
            $this->redirect(route('admin.data_verification.biodata.process'), navigate: true);
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-update-biodata', 'Ups.. terjadi kesalahan, silahkan coba lagi nanti!');
        }

        //Send message when biodata is Invalid
        if ($this->inputs['biodataStatus'] == VerificationStatusEnum::INVALID) {
            $studentPhone = $this->studentDetail->country_code . $this->studentDetail->mobile_phone;

            try {
                $message = MessageHelper::waInvalidBiodata($this->studentName, $this->branchName, $this->programName, $this->academicYear, $this->inputs['invalidReason']);
                WhaCenterHelper::sendText($studentPhone, $message);
            } catch (\Throwable $th) {
                logger($th);
                session()->flash('notification-failed', 'Notifikasi WA gagal dikirim, silahkan follow up secara manual');
            }
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.biodata.process.detail.detail-process-biodata-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'isRollOver' => true
            ]);
        }

        return view('livewire.web.admin.data-verification.biodata.process.detail.detail-process-biodata-admin')->layout('components.layouts.web.web-app');
    }
}
