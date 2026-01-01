<?php

namespace App\Livewire\Admin\Setting\School;

use App\Models\Core\Branch as CoreBranch;
use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Queries\Core\BranchQuery;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Crypt;

#[Title('Cabang Pondok')]
class Branch extends Component
{
    public $isMobile = false;

    #[Computed]
    public function listBranches() {
        return BranchQuery::getAllBranchTotalProgram();
    }

    //LISTENER - Get latest data when Create or Update success
    #[On('toast')]
    public function refetchListBranch() {
        $this->listBranches();
    }

    //HOOK - Execute every time component is render
    public function boot(MobileDetect $mobileDetect) {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ACTION - Execute delete branch data
    public function deleteBranch($id) {
        try {
            $realId = Crypt::decrypt($id);
            CoreBranch::where('id', $realId)->delete();

            $this->dispatch('toast', type: 'warning', message: 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            session()->flash('error-delete-owner', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render(MobileDetect $mobileDetect)
    {
        if ($mobileDetect->isMobile()) {
            return view('livewire.mobile.admin.setting.school.branch')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'link' => 'admin.setting.landing'

            ]);
        }
        return view('livewire.web.admin.setting.school.branch')->layout('components.layouts.web.web-app');
    }
}
