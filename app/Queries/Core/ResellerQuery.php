<?php

namespace App\Queries\Core;

use App\Models\Core\Reseller;

class ResellerQuery {

    public static function paginateResellerWithTransaction($limit) {
        return Reseller::baseEloquent()
        ->with([
            'transactionRecapitulation' => function ($query) {
                $query->select('id', 'total', 'amount');
            }
        ])
        ->orderBy('id', 'desc')
        ->paginate($limit);
    }

    public static function fetchProfileReseller($id) {
        return Reseller::baseEloquent(
            id: $id
        )
        ->with([
            'user' => function ($query) {
                $query->join('roles', 'users.role_id', 'roles.id')
                ->select('users.id', 'users.email', 'users.photo', 'roles.name as role_name');
            }
        ])
        ->select('id', 'name', 'country_code', 'mobile_phone', 'user_id')
        ->first();
    }
}
