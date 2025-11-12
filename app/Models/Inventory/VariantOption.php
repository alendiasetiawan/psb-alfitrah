<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantOption extends Model
{
    protected $fillable = [
        'variant_type_id',
        'name'
    ];

    /**
     * Get the variantType that owns the VariantOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variantType(): BelongsTo
    {
        return $this->belongsTo(VariantType::class);
    }
}
