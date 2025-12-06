<?php

namespace App\Models\Core;

use App\Models\AdmissionData\ParentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LastEducation extends Model
{
    protected $table = 'last_educations';
    protected $fillable = [
        'name'
    ];

    public function parentsModel(): HasMany
    {
        return $this->hasMany(ParentModel::class);
    }

    public function fatherLastEducations(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'father_last_education_id', 'id');
    }

    public function motherLastEducations(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'mother_last_education_id', 'id');
    }

    public function guardianLastEducations(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'guardian_last_education_id', 'id');
    }
}
