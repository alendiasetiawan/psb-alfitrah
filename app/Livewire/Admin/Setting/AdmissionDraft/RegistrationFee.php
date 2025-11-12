<?php

namespace App\Livewire\Admin\Setting\AdmissionDraft;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use App\Models\Core\Admission;
use Livewire\Attributes\Title;
use App\Helpers\AdmissionHelper;
use App\Queries\Core\BranchQuery;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;

#[Title('Biaya Pendaftaran Siswa Baru')]
class RegistrationFee extends Component
{
    //Boolean
    public $isMobile = false;
    //Array
    public $academicYearLists;
    //Integer
    public $selectedAdmissionId;
    //Object
    public $activeAdmission;

    #[Computed]
    public function feePerBranchProgramLists() {
        return BranchQuery::getBranchProgramWithFee($this->selectedAdmissionId);
    }

    //LISTENER - Get latest admission fee data when Create or Update success
    #[On('toast')]
    public function refetchFeePerBranchProgramLists() {
        $this->feePerBranchProgramLists();
    }

    //HOOK - Execute every time component is render
    public function boot(MobileDetect $mobileDetect) {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //HOOK - Execute once when component is rendered
    public function mount() {
        $this->academicYearLists = Admission::pluck('name', 'id');
        $this->activeAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $this->activeAdmission->id;
    }

    //HOOK - Execute when property is changed
    public function updated($propertyName) {
        if ($propertyName == 'selectedAdmissionId') {
            $this->activeAdmission = Admission::find($this->selectedAdmissionId);
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.setting.admission-draft.registration-fee')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }

        return view('livewire.web.admin.setting.admission-draft.registration-fee')->layout('components.layouts.web.web-app');
    }
}
