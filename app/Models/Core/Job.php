<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use App\Models\AdmissionData\ParentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    protected $fillable = [
        'name'
    ];

    public function fatherJobs(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'father_job_id', 'id');
    }

    public function motherJobs(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'mother_job_id', 'id');
    }

    public function guardianJobs(): HasMany
    {
        return $this->hasMany(ParentModel::class, 'guardian_job_id', 'id');
    }
}
