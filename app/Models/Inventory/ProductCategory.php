<?php

namespace App\Models\Inventory;

use App\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'hex_color',
        'image'
    ];

    /**
     * Get all of the products for the ProductCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public static function baseEloquent($search = null) {
        return ProductCategory::when(!empty($search), function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public static function baseQuery($search = null) {
        return DB::table('product_categories')
        ->where('deleted_at', null)
        ->when(!empty($search), function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        });;
    }
}
