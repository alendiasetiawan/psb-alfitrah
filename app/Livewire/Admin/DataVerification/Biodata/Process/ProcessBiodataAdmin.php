<?php

namespace App\Livewire\Admin\DataVerification\Biodata\Process;

use App\Helpers\AdmissionHelper;
use App\Queries\AdmissionData\AdmissionVerificationQuery;
use App\Queries\Core\AdmissionQuery;
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
    public string $searchStudent = '', $admissionYear = '', $title = '', $link = '';
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
        $this->admissionYear = AdmissionQuery::fetchAdmissionDetail($this->selectedAdmissionId)->name;
        $this->title = 'Proses Verifikasi Biodata';
        $this->link = 'admin.dashboard';
    }

    //ANCHOR: Action
    public function verifyStudent($studentId)
    {
        $this->redirectRoute('admin.data_verification.biodata.process.detail', ['studentId' => $studentId], navigate: true);
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.biodata.process.process-biodata-admin')->layout('components.layouts.mobile.mobile-app', [
                'isFixedTop' => true,
            ]);
        }
        return view('livewire.web.admin.data-verification.biodata.process.process-biodata-admin')->layout('components.layouts.web.web-app');
    }
}
