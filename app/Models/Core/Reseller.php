<?php

namespace App\Models\Core;

use App\Models\Transaction\TransactionRecapitulation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reseller extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'country_code',
        'mobile_phone'
    ];

    /**
     * Get the user that owns the Reseller
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactionRecapitulation associated with the Reseller
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transactionRecapitulation(): HasOne
    {
        return $this->hasOne(TransactionRecapitulation::class);
    }

    public static function baseEloquent($resellerName = null, $id = null) {
        return Reseller::when(!empty($resellerName), function ($query) use ($resellerName) {
            $query->where('name', 'like', '%' . $resellerName . '%');
        })
        ->when(!empty($id), function ($query) use ($id) {
            $query->where('id', $id);
        });
    }
}
