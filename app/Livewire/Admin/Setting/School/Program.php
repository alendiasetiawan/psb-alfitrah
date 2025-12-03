<?php

namespace App\Livewire\Admin\Setting\School;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use App\Models\Core\Admission;
use Livewire\Attributes\Title;
use App\Helpers\AdmissionHelper;
use App\Models\Core\EducationProgram;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Crypt;
use App\Queries\Core\EducationProgramQuery;

#[Title('Program Pendidikan')]
class Program extends Component
{
    //Array
    public $academicYearLists;
    //Integer
    public $selectedAdmissionId;
    //Boolean
    public $isMobile = false;

    protected AdmissionHelper $admissionHelper;

    #[Computed]
    public function listEducationPrograms()
    {
        return EducationProgramQuery::getProgramsBranchInAdmission($this->selectedAdmissionId);
    }

    //LISTENER - Get latest data when Create or Update success
    #[On('toast')]
    public function refetchListBranch()
    {
        $this->listEducationPrograms();
    }

    //HOOK - Execute every time component is render
    public function boot(AdmissionHelper $admissionHelper, MobileDetect $mobileDetect)
    {
        $this->admissionHelper = $admissionHelper;
        $this->isMobile = $mobileDetect->isMobile();
    }

    //HOOK - Execute once when component is rendered
    public function mount()
    {
        $this->academicYearLists = AdmissionHelper::getAdmissionYearLists();
        $this->selectedAdmissionId = $this->admissionHelper->activeAdmission()->id;
    }

    //ACTION - Delete data program
    public function deleteProgram($id)
    {
        try {
            $realIdProgram = Crypt::decrypt($id);
            EducationProgram::find($realIdProgram)->delete();

            $this->dispatch('toast', type: 'warning', message: 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            session()->flash('error-delete-program', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.setting.school.program')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }
        return view('livewire.web.admin.setting.school.program')->layout('components.layouts.web.web-app');
    }
}
