<?php

namespace App\Livewire\Admin\DataVerification\Biodata\Verified;

use App\Helpers\AdmissionHelper;
use App\Queries\AdmissionData\AdmissionVerificationQuery;
use App\Queries\Core\AdmissionQuery;
use App\Services\StudentDataService;
use Detection\MobileDetect;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Biodata Valid')]
class VerifiedBiodataAdmin extends Component
{
    use WithPagination;

    public bool $isMobile = false;
    public string $searchStudent = '', $admissionYear;
    public ?int $selectedAdmissionId = null, $limitData = 10, $setCount = 1, $filterAdmissionId;
    public object $admissionYearLists;

    protected StudentDataService $studentDataService;

    //ANCHOR: ACTION LOAD MORE
    #[On('load-more')]
    public function loadMore($loadItem)
    {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function verifiedStudentBiodatas()
    {
        return $this->studentDataService->paginateStudentVerifiedBiodata($this->selectedAdmissionId, $this->searchStudent, $this->limitData);
    }

    #[Computed]
    public function totalVerifiedBiodataStudent()
    {
        return AdmissionVerificationQuery::countStudentVerifiedBiodata($this->selectedAdmissionId);
    }

    //ANCHOR: Boot
    public function boot(MobileDetect $mobileDetect, StudentDataService $studentDataService)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->studentDataService = $studentDataService;
    }

    //ANCHOR: Mount
    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
        $this->setAdmissionYear();
    }

    public function updated($property) 
    {
        if ($property == 'searchStudent') {
            $this->setCount = 1;
        }
    }

    //ANCHOR: Execute when page number is updated
    public function updatedPage($page)
    {
        $setPage = $page - 1;
        $dataLoaded = $setPage * $this->limitData;
        $this->setCount = $dataLoaded + 1;
    }

    //ANCHOR: SET Value Filter
    public function setSelectedAdmissionId()
    {
        $this->selectedAdmissionId = $this->filterAdmissionId;
        $this->setAdmissionYear();
    }

    //ANCHOR: Action to set admission year
    public function setAdmissionYear()
    {
        $this->admissionYear = AdmissionQuery::fetchAdmissionDetail($this->selectedAdmissionId)->name;
    }

    //ANCHOR: Action to show student data
    public function showStudentData($studentId)
    {
        $this->redirectRoute('admin.data_verification.biodata.verified.detail', ['studentId' => $studentId], navigate: true);
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.biodata.verified.verified-biodata-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }
        return view('livewire.web.admin.data-verification.biodata.verified.verified-biodata-admin')->layout('components.layouts.web.web-app');
    }
}
