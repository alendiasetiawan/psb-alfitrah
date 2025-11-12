<?php

namespace App\Models\Core;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'country_code',
        'mobile_phone'
    ];

    /**
     * Get all of the stores for the Owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    /**
     * Get the user that owns the Owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public static function baseEloquent($ownerName = null, $ownerId = null) {
        return Owner::when(!empty($ownerName), function ($query) use ($ownerName) {
            $query->where('name', 'like', '%' . $ownerName . '%');
        })
        ->when(!empty($ownerId), function ($query) use ($ownerId) {
            $query->where('id', $ownerId);
        });
    }
}
