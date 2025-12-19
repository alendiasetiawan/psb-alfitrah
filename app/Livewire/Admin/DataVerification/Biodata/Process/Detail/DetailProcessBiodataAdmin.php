<?php

namespace App\Livewire\Admin\DataVerification\Biodata\Process\Detail;

use App\Enums\VerificationStatusEnum;
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
