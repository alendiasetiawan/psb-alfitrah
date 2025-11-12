<?php

namespace App\Models\Transaction;

use App\Models\Core\Reseller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionRecapitulation extends Model
{
    protected $fillable = [
        'reseller_id',
        'total',
        'amount'
    ];

    /**
     * Get the reseller that owns the TransactionRecapitulation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Reseller::class);
    }
}
