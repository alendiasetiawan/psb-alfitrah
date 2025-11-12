<?php

namespace App\Models\Core;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admission extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'status'
    ];
    
    public function admissionBatches(): HasMany
    {
        return $this->hasMany(AdmissionBatch::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function admissionQuotas(): HasMany
    {
        return $this->hasMany(AdmissionQuota::class);
    }

    public function admissionFees(): HasMany
    {
        return $this->hasMany(AdmissionFee::class);
    }
}
