<?php

namespace App\Queries\Inventory;

use App\Models\Inventory\Product;

class ProductQuery {

    public static function paginateProducts($limitData, $search = null, $productCategoryId = null, $storeId = null) {
        return Product::baseEloquent($search, $productCategoryId, $storeId)
        ->with([
            'productCategory' => function ($query) {
                $query->select('id', 'name')
                ->withTrashed();
            },
            'store' => function ($query) {
                $query->select('id', 'name')
                ->withTrashed();
            }
        ])
        ->orderBy('id', 'desc')
        ->paginate($limitData);
    }
}