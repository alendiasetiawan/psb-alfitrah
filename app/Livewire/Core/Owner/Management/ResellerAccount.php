<?php

namespace App\Livewire\Core\Owner\Management;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use App\Models\Core\Reseller;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Queries\Core\ResellerQuery;
use Illuminate\Support\Facades\Crypt;

#[Title('Akun Reseller')]
class ResellerAccount extends Component
{
    //Boolean
    public $isMobile = false;
    //Integer
    public $limitData = 15;

    #[Computed]
    public function listResellers() {
        return ResellerQuery::paginateResellerWithTransaction($this->limitData);
    }

    //HOOK - Execute every time component is render
    public function boot(MobileDetect $mobileDetect) {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ACTION - Refetch data when database updated
    #[On('toast')]
    public function fetchListReseller() {
        $this->listResellers();
    }

    //ACTION - Load more data
    #[On('load-more')]
    public function loadMore($loadItem) {
        $this->limitData += $loadItem;
    }

    //ACTION - Execute delete owner data
    public function deleteReseller($id) {
        try {
            $realId = Crypt::decrypt($id);
            $reseller = Reseller::find($realId);

            DB::transaction(function () use($reseller) {
                User::where('id', $reseller->user_id)->delete();
                $reseller->delete();
            });

            $this->dispatch('toast', type: 'warning', message: 'Data reseller berhasil dihapus!');
        } catch (\Throwable $th) {
            session()->flash('error-delete-reseller', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.core.owner.management.reseller-account')->layout('components.layouts.mobile.mobile-app',[
                'isShowTitle' => true,
                'isShowBottomNavbar' => true
            ]);
        }

        return view('livewire.core.owner.management.reseller-account')->layout('components.layouts.web.web-app');
    }
}
