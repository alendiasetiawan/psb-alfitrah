<?php

namespace App\Livewire\Visitor\StudentRegistration;

use App\Helpers\AdmissionHelper;
use App\Queries\Core\BranchQuery;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Title('Kuota Pendaftaran')]
class BranchQuota extends Component
{
    //Integer
    public $activeAdmissionId;
    //object
    public $activeAdmission;
    //Boolean
    public $isAdmissionOpen;

    protected AdmissionHelper $admissionHelper;

    #[Computed]
    public function branchQuotaLists() {
        return BranchQuery::getBranchProgramWithQuota($this->activeAdmission->id);
    }

    //HOOK - Execute once when component is rendered
    public function mount() {
        $this->activeAdmission = $this->admissionHelper::activeAdmission();
        $this->isAdmissionOpen = AdmissionHelper::isAdmissionOpen();
    }

    //HOOK - Execute every time component is rendered
    public function boot(AdmissionHelper $admissionHelper) {
        $this->admissionHelper = $admissionHelper;
    }

    //ACTION - Open registration form with selected branch_id
    public function openRegistrationForm($id) {
        $this->redirect(route('registration_form'), navigate: true);
        $this->dispatch('fill-registration-form', $id)->to(RegistrationForm::class);
    }

    public function render()
    {
        return view('livewire.web.visitor.student-registration.branch-quota')->layout('components.layouts.web.web-blank-header');
    }
}
