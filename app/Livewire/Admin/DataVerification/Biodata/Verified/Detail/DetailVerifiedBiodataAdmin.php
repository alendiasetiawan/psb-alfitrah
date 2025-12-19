<?php

namespace App\Livewire\Admin\DataVerification\Biodata\Verified\Detail;

use App\Queries\AdmissionData\StudentQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Biodata Santri')]
class DetailVerifiedBiodataAdmin extends Component
{
    public string $studentId = '';
    public object $studentQuery;
    public bool $isMobile = false;

    //ANCHOR: Boot hook
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR: Mount hook
    public function mount()
    {
        try {
            $realStudentId = Crypt::decrypt($this->studentId);
            $this->studentQuery = StudentQuery::fetchStudentDetailWithStatus($realStudentId);
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-show-student', 'Mohon maaf, data tidak ditemukan');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.biodata.verified.detail.detail-verified-biodata-admin')->layout('components.layouts.mobile.mobile-app', [
                'isRollOver' => true,
                'isShowBackButton' => true
            ]);
        }

        return view('livewire.web.admin.data-verification.biodata.verified.detail.detail-verified-biodata-admin')->layout('components.layouts.web.web-app');
    }
}
