<?php

namespace App\Livewire\Inventory\Owner\Warehouse\Product;

use App\Models\Core\Store;
use App\Models\Inventory\ProductCategory;
use App\Models\Inventory\VariantType;
use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\Title;

#[Title('Tambah Produk')]
class AddProduct extends Component
{
    //Boolean
    public $isMobile = false, $isEditingMode = false, $isEditing = false;
    //Object
    public $categoryLists, $storeLists, $variantTypeLists;
    //Array
    public $inputs = [
        'selectedCategoryId' => '',
        'selectedStoreId' => '',
    ];

    //HOOK - Excute once when component is rendered
    public function mount() {
        $this->categoryLists = ProductCategory::pluck('name', 'id');
        $this->storeLists = Store::pluck('name', 'id');
        $this->variantTypeLists = VariantType::all();
    }

    //HOOK - Execute every time component is rendered
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.inventory.owner.warehouse.product.add-product')->layout('components.layouts.mobile.mobile-app',[
                'isShowBackButton' => true,
                'link'  => 'owner.warehouse.product.list_product'
            ]);
        }
        return view('livewire.inventory.owner.warehouse.product.add-product')->layout('components.layouts.web.web-app');
    }
}
