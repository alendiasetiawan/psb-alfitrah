<?php

namespace App\Livewire\Inventory\Owner\Warehouse\Product;

use App\Queries\Inventory\ProductQuery;
use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Title('Produk')]
class ListProduct extends Component
{
    //Boolean
    public $isMobile = false;
    //Integer
    public $limitData = 15, $productCategoryId = null, $storeId = null;
    //String
    public $search = null;

    #[Computed]
    public function productLists() {
        return ProductQuery::paginateProducts($this->limitData, $this->search, $this->productCategoryId, $this->storeId);
    }

    //HOOK - Execute every time component is rendered
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.inventory.owner.warehouse.product.list-product')->layout('components.layouts.mobile.mobile-app',[
                'isShowTitle' => true,
                'isShowBottomNavbar' => true
            ]);
        }

        return view('livewire.inventory.owner.warehouse.product.list-product')->layout('components.layouts.web.web-app');
    }
}
