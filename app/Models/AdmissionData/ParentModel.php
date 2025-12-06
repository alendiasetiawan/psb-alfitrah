<?php

namespace App\Models\AdmissionData;

use App\Models\User;
use App\Models\Core\Job;
use App\Models\Core\Sallary;
use App\Models\Core\LastEducation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $fillable = [
        'user_id',
        'is_parent',
        'father_name',
        'father_birth_place',
        'father_birth_date',
        'father_address',
        'father_country_code',
        'father_mobile_phone',
        'father_last_education_id',
        'father_job_id',
        'father_sallary_id',
        'mother_name',
        'mother_birth_place',
        'mother_birth_date',
        'mother_address',
        'mother_country_code',
        'mother_mobile_phone',
        'mother_last_education_id',
        'mother_job_id',
        'mother_sallary_id',
        'guardian_name',
        'guardian_birth_place',
        'guardian_birth_date',
        'guardian_address',
        'guardian_country_code',
        'guardian_mobile_phone',
        'guardian_last_education_id',
        'guardian_job_id',
        'guardian_sallary_id'
    ];

    public function lastEducation(): BelongsTo
    {
        return $this->belongsTo(LastEducation::class);
    }

    public function sallary(): BelongsTo
    {
        return $this->belongsTo(Sallary::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function multiStudent(): HasOne
    {
        return $this->hasOne(MultiStudent::class);
    }

    //Father Table Relationship
    public function educationFather(): BelongsTo
    {
        return $this->belongsTo(LastEducation::class, 'father_last_education_id', 'id');
    }

    public function jobFather(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'father_job_id', 'id');
    }

    public function sallaryFather(): BelongsTo
    {
        return $this->belongsTo(Sallary::class, 'father_sallary_id', 'id');
    }

    //Mother Table Relationship
    public function educationMother(): BelongsTo
    {
        return $this->belongsTo(LastEducation::class, 'mother_last_education_id', 'id');
    }

    public function sallaryMother(): BelongsTo
    {
        return $this->belongsTo(Sallary::class, 'mother_sallary_id', 'id');
    }

    public function jobMother(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'mother_job_id', 'id');
    }

    //Guardian Table Relationship
    public function educationGuardian(): BelongsTo
    {
        return $this->belongsTo(LastEducation::class, 'guardian_last_education_id', 'id');
    }

    public function jobGuardian(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'guardian_job_id', 'id');
    }

    public function sallaryGuardian(): BelongsTo
    {
        return $this->belongsTo(Sallary::class, 'guardian_sallary_id', 'id');
    }
}
