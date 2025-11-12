<?php

namespace App\Queries\Core;

use App\Models\Core\Store;

class StoreQuery {

    public static function paginateStores($limit) {
        return Store::baseEloquent()
        ->with([
            'owner' => function ($query) {
                $query->select('id', 'name')
                ->withTrashed();
            },
        ])
        ->withCount('products as total_products')
        ->orderBy('id', 'desc')
        ->paginate($limit);
    }

    public static function fetchStore($id) {
        return Store::baseEloquent()
        ->with([
            'owner' => function ($query) {
                $query->select('id', 'name')
                ->withTrashed();
            },
        ])
        ->withCount('products as total_products')
        ->where('id', $id)
        ->first();
    }

}
