<?php

namespace App\Models\Core;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationProgram extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'name',
        'description'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
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

    public static function baseEloquent($branchId = null) {
        return self::when(!empty($branchId), function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        });
    }
}
