<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    public const REGISTRATION_ID = 1;

    protected $fillable = [
        'id',
        'bank_name',
        'account_number',
        'account_name',
    ];

    public function scopeBaseEloquent(Builder $query): Builder
    {
        return $query->whereKey(self::REGISTRATION_ID)
            ->select('id', 'bank_name', 'account_number', 'account_name');
    }
}
