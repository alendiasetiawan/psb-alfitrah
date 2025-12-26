<?php

namespace App\Livewire\Admin\TestResult;

use App\Enums\OrderDataEnum;
use App\Helpers\AdmissionHelper;
use App\Models\Core\Branch;
use App\Queries\Core\AdmissionBatchQuery;
use App\Queries\Core\AdmissionQuery;
use App\Queries\Core\BranchQuery;
use App\Queries\PlacementTest\PlacementTestResultQuery;
use Detection\MobileDetect;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Hasil Tes Calon Siswa')]
class TestResultAdmin extends Component
{
    use WithPagination;
    
    public bool $isMobile = false, $isFilterActive = false, $showAlertNotification = false;
    public $limitData = 10, $setCount = 1, $selectedAdmissionBatchId = '', $selectedAdmissionId, $selectedBranchId = '', $filterAdmissionBatchId = '', $filterBranchId = '',  $filterAdmissionId = '';
    public string $searchStudent = '', $admissionYear = '', $admissionBatchName = '', $branchName = '', $selectedOrderBy = '', $selectedPublicationStatus = '';
    public object $admissionYearLists, $admissionBatchLists, $branchLists;

    #[On('notification-failed')]
    public function showAlert() {
        $this->showAlertNotification = true;
    }

    #[On('toast')]
    public function reFetchData()
    {
        $this->reset('searchStudent');
        $this->testResultStudents();
        $this->totalResult();
    }

    #[Computed]
    public function testResultStudents(): object
    {
        return PlacementTestResultQuery::paginateStudentTestResults($this->limitData, $this->selectedOrderBy, $this->selectedAdmissionId, $this->selectedAdmissionBatchId, $this->searchStudent, $this->selectedBranchId);
    }

    #[Computed]
    public function totalResult(): array
    {
        return PlacementTestResultQuery::countTotalResult($this->selectedAdmissionId, $this->selectedAdmissionBatchId, $this->selectedBranchId);
    }

    //ANCHOR: Mount
    public function mount()
    {
        //Set predefined value
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
        $this->setAdmissionId();
        $this->filterAdmissionId = $this->selectedAdmissionId;
        $this->setAdmissionBatchLists();
        $this->setAdmissionYear();
        $this->branchLists = BranchQuery::pluckAllBranch();
        $this->selectedOrderBy = OrderDataEnum::PUBLICATION;
    }

    //ANCHOR: Boot hook
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR: Updated hook
    public function updated($property)
    {
        $this->resetPage();

        if ($property == 'searchStudent') {
            $this->setCount = 1;
        }

        if ($property == 'filterAdmissionId') {
            $this->setAdmissionBatchLists();
        }

        if (in_array($property, ['filterAdmissionBatchId', 'filterBranchId'])) {
            $this->isFilterActive = true;
        }
    }

    //ANCHOR - HANDLE NUMBER ON PAGE UPDATE
    public function updatedPage($page)
    {
        $setPage = $page - 1;
        $dataLoaded = $setPage * $this->limitData;
        $this->setCount = $dataLoaded + 1;
    }

    //ANCHOR: Action to set admission year
    public function setAdmissionYear()
    {
        $this->admissionYear = AdmissionQuery::fetchAdmissionDetail($this->selectedAdmissionId)->name;
    }
    
    //ANCHOR: Action to set admission batch name
    public function setAdmissionBatchName()
    {
        $this->admissionBatchName = AdmissionBatchQuery::fetchAdmissionBatchDetail($this->selectedAdmissionBatchId)->name;
    }

    //ANCHOR: Action to set admission batch
    public function setAdmissionBatchLists()
    {
        $this->admissionBatchLists = AdmissionHelper::getAdmissionBatchLists($this->selectedAdmissionId);
    }

    //ANCHOR: Set admission ID
    public function setAdmissionId()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
    }

    public function setBranchName()
    {
        $this->branchName = Branch::find($this->selectedBranchId)->name;
    }

    //ANCHOR: SET Value Filter
    public function setFilter()
    {
        if (!empty($this->filterAdmissionId)) {
            $this->selectedAdmissionId = $this->filterAdmissionId;
            $this->setAdmissionYear();
        }

        if (!empty($this->filterBranchId)) {
            $this->selectedBranchId = $this->filterBranchId;
            $this->setBranchName();
        }

        if (!empty($this->filterAdmissionBatchId)) {
            $this->selectedAdmissionBatchId = $this->filterAdmissionBatchId;
            $this->setAdmissionBatchName();
        }
        
        $this->isFilterActive = true;
    }

    public function resetFilter()
    {
        $this->reset('selectedAdmissionId', 'selectedAdmissionBatchId', 'selectedBranchId', 'filterAdmissionId', 'filterAdmissionBatchId', 'filterBranchId');
        $this->setAdmissionId();
        $this->setAdmissionYear();
        $this->setAdmissionBatchLists();
        $this->testResultStudents();
        $this->totalResult();
        $this->isFilterActive = false;
    }

    //ANCHOR: Release all student with hold status and result "Menunggu"
    public function releaseResult()
    {

    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.test-result.test-result-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }
        return view('livewire.web.admin.test-result.test-result-admin')->layout('components.layouts.web.web-app');
    }
}
