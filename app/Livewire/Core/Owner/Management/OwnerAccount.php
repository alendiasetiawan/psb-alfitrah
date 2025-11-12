<?php

namespace App\Livewire\Core\Owner\Management;

use App\Models\User;
use Livewire\Component;
use App\Models\Core\Owner;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Queries\Core\OwnerQuery;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

#[Title('Akun Owner')]
class OwnerAccount extends Component
{
    //Integer
    public $limitData = 15;
    //Boolean
    public $isMobile = false;

    #[Computed]
    public function listOwners() {
        return OwnerQuery::paginateOwnerWithStore($this->limitData);
    }

    //HOOK - Execute every time component is render
    public function boot(MobileDetect $mobileDetect) {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ACTION - Refetch data list owners when data is created or updated
    #[On('toast')]
    public function fetchListOwner() {
        $this->listOwners();
    }

    //ACTION - Load more data
    #[On('load-more')]
    public function loadMore($loadItem) {
        $this->limitData += $loadItem;
    }

    //ACTION - Execute delete owner data
    public function deleteOwner($id) {
        try {
            $realId = Crypt::decrypt($id);
            $owner = Owner::find($realId);

            DB::transaction(function () use($owner) {
                User::where('id', $owner->user_id)->delete();
                $owner->delete();
            });

            $this->dispatch('toast', type: 'warning', message: 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            session()->flash('error-delete-owner', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.core.owner.management.owner-account')->layout('components.layouts.mobile.mobile-app',[
                'isShowTitle' => true,
                'isShowBottomNavbar' => true
            ]);
        }

        return view('livewire.core.owner.management.owner-account')->layout('components.layouts.web.web-app');
    }


}
