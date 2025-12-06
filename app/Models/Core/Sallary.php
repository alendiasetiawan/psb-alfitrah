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

    public function fatherSallaries(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'father_sallary_id', 'id');
    }

    public function motherSallaries(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'mother_sallary_id', 'id');
    }

    public function guardianSallaries(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'guardian_sallary_id', 'id');
    }
}
