<?php

namespace App\Models\Inventory;

use App\Models\Core\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'store_id',
        'name',
        'description',
        'base_price',
        'reseller_price',
        'stock'
    ];

    /**
     * Get the store that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the productCategory that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public static function baseEloquent($search = null, $productCategoryId = null, $storeId = null) {
        return Product::when(!empty($search), function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
        ->when(!empty($productCategoryId), function ($query) use ($productCategoryId) {
            return $query->where('product_category_id', $productCategoryId);
        })
        ->when(!empty($storeId), function ($query) use ($storeId) {
            return $query->where('store_id', $storeId);
        });
    }
}
