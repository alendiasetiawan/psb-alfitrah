<?php

namespace App\Queries\Core;

use App\Models\Core\Owner;
use Illuminate\Support\Facades\DB;

class OwnerQuery {

    public static function getOwnerBySearchName($search) {
        return DB::table('owners')->where('name', 'like', '%' . $search . '%')->get();
    }

    public static function getOwnerLimit($limit) {
        return DB::table('owners')->limit($limit)->get();
    }

    public static function paginateOwnerWithStore($limitData) {
        return Owner::baseEloquent()
        ->with([
            'stores' => function ($query) {
                $query->select('id', 'name');
            },
            'user' => function ($query) {
                $query->select('id', 'email', 'photo');
            }
        ])
        ->withCount('stores as total_store')
        ->orderBy('id', 'desc')
        ->paginate($limitData);
    }

    public static function fetchProfileOwner($ownerId) {
        return Owner::baseEloquent(
            ownerId: $ownerId
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
