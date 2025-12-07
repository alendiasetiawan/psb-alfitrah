<?php

namespace App\Livewire\Admin\MasterData\StudentDatabase;

use App\Queries\AdmissionData\StudentQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Data Siswa')]
class DetailStudentDatabase extends Component
{
    public bool $isMobile = false;
    public string $studentId = '';
    public object $studentQuery;

    public function mount($studentId)
    {
        $realId = Crypt::decrypt($studentId);
        $this->studentQuery = StudentQuery::fetchStudentDetailWithAttachment($realId);
    }

    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.master-data.student-database.detail-student-database')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'isShowTitle' => true,
                'isRollOver' => true
            ]);
        }
        return view('livewire.web.admin.master-data.student-database.detail-student-database')->layout('components.layouts.web.web-app');
    }
}
