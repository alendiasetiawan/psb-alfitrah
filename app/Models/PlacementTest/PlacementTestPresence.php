<?php

namespace App\Models\PlacementTest;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementTestPresence extends Model
{
    protected $fillable = [
        'student_id',
        'check_in_time'
    ];

    public function scopeJoinStudent($query)
    {
        return $query->join('students', 'placement_test_presences.student_id', 'students.id')
        ->addSelect('students.id', 'branch_id', 'education_program_id', 'gender', 'name as student_name', 'admission_id', 'admission_batch_id', 'mobile_phone', 'country_code');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
