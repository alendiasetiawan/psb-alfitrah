<?php

namespace App\Models\Core;

use App\Models\AdmissionData\ParentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sallary extends Model
{
    protected $fillable = [
        'name'
    ];

    public function parentsModel(): HasMany
    {
        return $this->hasMany(ParentModel::class);
    }
}
