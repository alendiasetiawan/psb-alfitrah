<?php

namespace App\Livewire\Admin\Setting\AdmissionDraft;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use App\Models\Core\Admission;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Models\Core\AdmissionBatch;
use App\Queries\Core\AdmissionQuery;
use Illuminate\Support\Facades\Crypt;

#[Title('Tahun Ajaran PSB')]
class AcademicYear extends Component
{
    //Boolean
    public $isMobile;
    //Integer
    public $limitData = 6;

    #[Computed]
    public function listAcademicYears() {
        return AdmissionQuery::paginateAdmissionWithBatches($this->limitData);
    }

    //LISTENER - Refetch List Academic Year when Create or Update success
    #[On('toast')]
    public function refetchListAcademicYears() {
        $this->listAcademicYears();
    }

    //LISTENER - Load more data
    #[On('load-more')]
    public function loadMoreAcademicYears($loadItem) {
        $this->limitData += $loadItem;
    }

    //HOOK - Execute every time component is render
    public function boot(MobileDetect $mobileDetect) {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ACTION - Delete academic year
    public function deleteAdmission($id) {
        try {
            $admissionHasStudent = Admission::find(Crypt::decrypt($id))->students()->exists();

            if ($admissionHasStudent) {
                session()->flash('error-delete-program', 'Tidak dapat menghapus tahun akademik yang memiliki siswa!');
                return;
            }

            $realAdmissionId = Crypt::decrypt($id);
            Admission::find($realAdmissionId)->delete();

            $this->dispatch('toast', type: 'warning', message: 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            session()->flash('error-delete-program', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    //ACTION - Delete admission batch
    public function deleteAdmissionBatch($id) {
        try {
            $realId = Crypt::decrypt($id);
            AdmissionBatch::where('id', $realId)->delete();
            $this->dispatch('toast', type: 'warning', message: 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            session()->flash('error-delete-admission-batch', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.setting.admission-draft.academic-year')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'link' => 'admin.setting.landing'
            ]);
        }
        return view('livewire.web.admin.setting.admission-draft.academic-year')->layout('components.layouts.web.web-app');
    }
}
