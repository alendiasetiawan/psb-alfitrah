<?php

namespace App\Livewire\Inventory\Owner\Warehouse\Product;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\Inventory\ProductCategory;
use App\Queries\Inventory\ProductCategoryQuery;

#[Title('Kategori Produk')]
class Category extends Component
{
    //Boolean
    public $isMobile = false;
    //Integer
    public $totalCategory = 0, $limitData = 5;
    //String
    public $searchCategory = null;

    #[Computed]
    public function categoryLists() {
        return ProductCategoryQuery::paginateCategoryWithProduct($this->limitData, $this->searchCategory);
    }

    //HOOK - Execute every time component is rendered
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->totalCategory = ProductCategory::baseQuery()->count();
    }

    //ACTION - Refetch data when database updated
    #[On('toast')]
    public function fetchCategoryLists() {
        $this->categoryLists();
    }

    //ACTION - Reload data
    #[On('load-more')]
    public function loadMore($loadItem) {
        $this->limitData += $loadItem;
    }

    //ACTION - Delete category data
    public function deleteCategory($id) {
        try {
            $decryptId = Crypt::decrypt($id);
            ProductCategory::find($decryptId)->delete();
            $this->dispatch('toast', type: 'warning', message: 'Kategori berhasil dihapus!');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('error-delete-category', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.inventory.owner.warehouse.product.category')->layout('components.layouts.mobile.mobile-app',[
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }
        return view('livewire.inventory.owner.warehouse.product.category')->layout('components.layouts.web.web-app');
    }
}
