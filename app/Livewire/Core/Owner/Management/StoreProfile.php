<?php

namespace App\Livewire\Core\Owner\Management;

use Livewire\Component;
use App\Models\Core\Store;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Queries\Core\StoreQuery;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Computed;
use PhpParser\Node\Expr\Throw_;

#[Title('Profil Toko')]
class StoreProfile extends Component
{
    //Object
    public $listOfOwners, $storeData = null;
    //String
    public $query, $selectedText, $realId;
    //Integer
    public $selectedValue, $limitData =  15;
    //Array
    public $results = [];

    #[On('load-more')]
    public function loadMore($loadItem) {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function listStores() {
        return StoreQuery::paginateStores($this->limitData);
    }

    //ACTION - Refetch data when database updated
    #[On('toast')]
    public function fetchListStore() {
        $this->listStores();
    }

    //ACTION - Set value for selected store
    public function setDeleteValue($id) {
        try {
            $this->realId = Crypt::decrypt($id);
            $this->storeData = Store::find($this->realId);
        } catch (DecryptException) {
            session()->flash('error-id-delete', 'Dilarang modifikasi ID parameter');
        }
    }

    public function deleteStore() {
        try {
            Store::where('id', $this->realId)->delete();
            $this->dispatch('toast', type: 'success', message: 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            session()->flash('error-delete-store', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render(MobileDetect $mobileDetect)
    {
        if ($mobileDetect->isMobile()) {
            return view('livewire.core.owner.management.store-profile')->layout('components.layouts.mobile.mobile-app',[
                'isShowTitle' => true,
                'isShowBottomNavbar' => true
            ]);
        }

        return view('livewire.core.owner.management.store-profile')->layout('components.layouts.web.web-app');
    }
}
