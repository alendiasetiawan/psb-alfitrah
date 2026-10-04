<?php

namespace App\Livewire\Visitor\StudentRegistration;

use App\Helpers\AdmissionHelper;
use App\Models\Core\Admission;
use App\Queries\Core\BranchQuery;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kuota Pendaftaran')]
class BranchQuota extends Component
{
    public ?int $activeAdmissionId = null;

    public ?Admission $activeAdmission = null;

    public bool $isAdmissionOpen = false;

    protected AdmissionHelper $admissionHelper;

    #[Computed]
    public function branchQuotaLists(): Collection
    {
        if ($this->activeAdmission === null) {
            return new Collection;
        }

        return BranchQuery::getBranchProgramWithQuota($this->activeAdmission->id);
    }

    // HOOK - Execute once when component is rendered
    public function mount(): void
    {
        $this->activeAdmission = $this->admissionHelper::activeAdmission();
        $this->activeAdmissionId = $this->activeAdmission?->id;
        $this->isAdmissionOpen = $this->activeAdmission !== null && AdmissionHelper::isAdmissionOpen();
    }

    // HOOK - Execute every time component is rendered
    public function boot(AdmissionHelper $admissionHelper): void
    {
        $this->admissionHelper = $admissionHelper;
    }

    // ACTION - Open registration form with selected branch_id
    public function openRegistrationForm($id)
    {
        $this->redirect(route('registration_form'), navigate: true);
        $this->dispatch('fill-registration-form', $id)->to(RegistrationForm::class);
    }

    public function render(): View
    {
        return view('livewire.web.visitor.student-registration.branch-quota')->layout('components.layouts.web.web-blank-header');
    }
}
