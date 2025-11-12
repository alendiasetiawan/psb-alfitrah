<?php

use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;
use App\Livewire\Core\Owner\OwnerDashboard;
use App\Livewire\Core\Owner\Management\OwnerAccount;
use App\Livewire\Core\Owner\Management\StoreProfile;
use App\Livewire\Core\Owner\Management\ResellerAccount;
use App\Livewire\Inventory\Owner\Warehouse\Product\Category;
use App\Livewire\Inventory\Owner\Warehouse\Product\AddProduct;
use App\Livewire\Inventory\Owner\Warehouse\Product\ListProduct;
use App\Livewire\Inventory\Owner\Warehouse\Product\DetailProduct;

Route::middleware('role:'.RoleEnum::OWNER.'')->group(function () {
    Route::group(['prefix' => 'owner', 'as' => 'owner.'], function () {
        Route::get('/dashboard', OwnerDashboard::class)->name('dashboard');

        Route::group(['prefix' => 'management', 'as' => 'management.'], function () {
            Route::get('/store-profile', StoreProfile::class)->name('store_profile');
            Route::get('/owner-account', OwnerAccount::class)->name('owner_account');
            Route::get('/reseller-account', ResellerAccount::class)->name('reseller_account');
        });

        Route::group(['prefix' => 'warehouse', 'as' => 'warehouse.'], function () {
            Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
                Route::get('/category', Category::class)->name('category');
                Route::get('/add-product', AddProduct::class)->name('add_product');
                Route::get('/list-product', ListProduct::class)->name('list_product');
                Route::get('/detail-product/{productId}', DetailProduct::class)->name('detail_product');
            });
        });
    });
});