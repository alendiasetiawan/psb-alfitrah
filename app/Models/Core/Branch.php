<?php

namespace App\Models\Core;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'mobile_phone',
        'map_link',
        'photo'
    ];

    public function educationPrograms(): HasMany
    {
        return $this->hasMany(EducationProgram::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
