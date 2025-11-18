<?php

namespace App\Models\AdmissionData;

use App\Models\User;
use App\Models\Core\Admission;
use App\Models\Core\AdmissionBatch;
use App\Models\Core\Branch;
use App\Models\Core\District;
use App\Models\Core\EducationProgram;
use App\Models\Core\Province;
use App\Models\Core\Regency;
use App\Models\Core\Village;
use App\Models\StudentTest\PlacementTestResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'branch_id',
        'education_program_id',
        'admission_id',
        'admission_batch_id',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'reg_number',
        'name',
        'gender', //Enum ("Laki-Laki", "Perempuan")
        'birth_place',
        'birth_date',
        'address',
        'country_code',
        'mobile_phone',
        'nisn',
        'old_school_name',
        'old_school_address',
        'npsn',
        'is_scholarship',
        'is_walkout'
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function admissionBatch(): BelongsTo
    {
        return $this->belongsTo(AdmissionBatch::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class);
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function registrationPayment(): HasOne
    {
        return $this->hasOne(RegistrationPayment::class);
    }

    public function admissionVerification(): HasOne
    {
        return $this->hasOne(AdmissionVerification::class);
    }

    public function placementTestResult(): HasOne
    {
        return $this->hasOne(PlacementTestResult::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function multiStudent(): HasOne
    {
        return $this->hasOne(MultiStudent::class);
    }

    public function studentAttachment(): HasOne
    {
        return $this->hasOne(StudentAttachment::class);
    }
}
