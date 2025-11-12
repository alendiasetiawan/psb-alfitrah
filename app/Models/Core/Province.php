<?php

namespace App\Models\Core;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
