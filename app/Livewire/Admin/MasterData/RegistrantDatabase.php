<?php

namespace App\Livewire\Admin\MasterData;

use App\Helpers\AdmissionHelper;
use App\Queries\AdmissionData\StudentQuery;
use Detection\MobileDetect;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Database Pendaftar')]
class RegistrantDatabase extends Component
{
    use WithPagination;

    public bool $isMobile = false;
    public string $searchStudent = '';
    public ?int $selectedAdmissionId = null, $limitData = 15, $totalStudent;
    public object $admissionYearLists;

    protected AdmissionHelper $admissionHelper;

    #[Computed]
    public function registrantLists()
    {
        return StudentQuery::paginateStudentRegistrant(
            searchStudent: $this->searchStudent,
            selectedAdmissionId: $this->selectedAdmissionId,
            limitData: $this->limitData
        );
    }

    public function boot(MobileDetect $mobileDetect, AdmissionHelper $admissionHelper)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->admissionHelper = $admissionHelper;
    }

    public function mount()
    {
        $queryAdmission = $this->admissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
        $this->totalStudent = StudentQuery::countStudentRegistrant($this->selectedAdmissionId);
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.master-data.registrant-database')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }
        return view('livewire.web.admin.master-data.registrant-database')->layout('components.layouts.web.web-app');
    }
}
