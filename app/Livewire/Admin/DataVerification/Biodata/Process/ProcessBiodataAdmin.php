<?php

namespace App\Livewire\Admin\DataVerification\Biodata\Process;

use App\Helpers\AdmissionHelper;
use App\Queries\AdmissionData\AdmissionVerificationQuery;
use App\Services\StudentDataService;
use Detection\MobileDetect;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Proses Verifikasi Biodata')]
class ProcessBiodataAdmin extends Component
{
    public bool $isMobile = false;
    public object $admissionYearLists;
    public string $searchStudent = '';
    public ?int $selectedAdmissionId = null, $limitData = 9;

    protected StudentDataService $studentDataService;

    //ANCHOR: ACTION LOAD MORE
    #[On('load-more')]
    public function loadMore($loadItem)
    {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function processBiodataStudents()
    {
        return $this->studentDataService->paginateStudentProcessBiodata($this->selectedAdmissionId, $this->searchStudent, $this->limitData);
    }

    #[Computed]
    public function totalProcessBiodataStudent()
    {
        return AdmissionVerificationQuery::countStudentProcessBiodata($this->selectedAdmissionId);
    }

    public function boot(MobileDetect $mobileDetect, StudentDataService $studentDataService)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->studentDataService = $studentDataService;
    }

    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
    }

    //ANCHOR: Action
    public function verifyStudent(int $studentId)
    {
        dd($studentId);
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.biodata.process.process-biodata-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }
        return view('livewire.web.admin.data-verification.biodata.process.process-biodata-admin')->layout('components.layouts.web.web-app');
    }
}
