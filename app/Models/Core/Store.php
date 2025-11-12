<?php

namespace App\Models\Core;

use App\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'description',
        'logo'
    ];

    /**
     * Get the owner that owns the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Get all of the products for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public static function baseEloquent($ownerId = null) {
        return self::when(!empty($ownerId), function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        });
    }
}
