<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariantType extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the variantOptions for the VariantType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variantOptions(): HasMany
    {
        return $this->hasMany(VariantOption::class);
    }
}
