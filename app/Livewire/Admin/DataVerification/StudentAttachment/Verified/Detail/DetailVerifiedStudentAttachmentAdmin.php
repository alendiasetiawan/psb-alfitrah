<?php

namespace App\Livewire\Admin\DataVerification\StudentAttachment\Verified\Detail;

use App\Queries\AdmissionData\StudentQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Berkas Santri')]
class DetailVerifiedStudentAttachmentAdmin extends Component
{
    public string $studentId = '';
    public object $attachmentDetail;
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
            $this->attachmentDetail = StudentQuery::fetchStudentAttachmentWithStatus($realStudentId);
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-show-attachment', 'Mohon maaf, data tidak ditemukan');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.student-attachment.verified.detail.detail-verified-student-attachment-admin')->layout('components.layouts.mobile.mobile-app', [
                'isRollOver' => true,
                'isShowBackButton' => true
            ]);
        }
        return view('livewire.web.admin.data-verification.student-attachment.verified.detail.detail-verified-student-attachment-admin')->layout('components.layouts.web.web-app');
    }
}
