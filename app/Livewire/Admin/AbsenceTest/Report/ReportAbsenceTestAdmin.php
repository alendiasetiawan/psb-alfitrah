<?php

namespace App\Livewire\Admin\AbsenceTest\Report;

use App\Helpers\AdmissionHelper;
use App\Queries\Core\AdmissionBatchQuery;
use App\Queries\Core\AdmissionQuery;
use App\Queries\PlacementTest\PlacementTestPresenceQuery;
use Detection\MobileDetect;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Rekap Kehadiran Tes')]
class ReportAbsenceTestAdmin extends Component
{
    use WithPagination;

    public bool $isMobile = false, $isFilterActive = false;
    public $limitData = 10, $setCount = 1, $admissionId = null, $filterAdmissionId = null, $selectedAdmissionBatchId = '', $selectedAdmissionId, $filterAdmissionBatchId = '';
    public string $searchStudent = '', $admissionYear = '', $admissionBatchName = '';
    public object $admissionYearLists, $admissionBatchLists;

    //ANCHOR: ACTION LOAD MORE
    #[On('load-more')]
    public function loadMore($loadItem)
    {
        $this->limitData += $loadItem;
    }

    #[On('toast')]
    public function reloadData()
    {
        $this->resetPage();
        $this->presenceReportStudents();
    }

    #[Computed]
    public function presenceReportStudents()
    {
        return PlacementTestPresenceQuery::presenceReports($this->searchStudent, $this->selectedAdmissionId, $this->selectedAdmissionBatchId, $this->limitData);
    }

    //ANCHOR: Boot hook
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR: Mount
    public function mount()
    {
        $this->setAdmissionId();
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
        $this->setAdmissionBatchLists();
        $this->setAdmissionYear();
    }

    //ANCHOR: Updated Hook
    public function updated($property)
    {
        $this->resetPage();
        if ($property == 'selectedAdmissionId') {
            $this->setAdmissionBatchLists();
        }

        if (in_array($property, ['selectedAdmissionId', 'selectedAdmissionBatchId'])) {
            $this->isFilterActive = true;
        }
    }

    //ANCHOR: SET Value Filter
    public function setFilter()
    {
        if (!empty($this->filterAdmissionId)) {
            $this->selectedAdmissionId = $this->filterAdmissionId;
        }
        
        $this->selectedAdmissionBatchId = $this->filterAdmissionBatchId;
        $this->setAdmissionYear();
        $this->setAdmissionBatchName();
        $this->isFilterActive = true;
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

    public function setAdmissionId()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
    }

    public function resetFilter()
    {
        $this->reset('selectedAdmissionId', 'selectedAdmissionBatchId', 'filterAdmissionId', 'filterAdmissionBatchId');
        $this->presenceReportStudents();
        $this->setAdmissionId();
        $this->isFilterActive = false;
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.absence-test.report.report-absence-test-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }

        return view('livewire.web.admin.absence-test.report.report-absence-test-admin')->layout('components.layouts.web.web-app');
    }
}
