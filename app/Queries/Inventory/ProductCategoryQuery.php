<?php

namespace App\Queries\Inventory;

use App\Models\Inventory\ProductCategory;

class ProductCategoryQuery {

    public static function paginateCategoryWithProduct($limitData, $search = null) {
        return ProductCategory::baseEloquent($search)
        ->withCount('products as total_product')
        ->paginate($limitData);
    }

    public static function fetchCategory($id) {
        return ProductCategory::baseQuery()
        ->where('id', $id)
        ->first();
    }
}