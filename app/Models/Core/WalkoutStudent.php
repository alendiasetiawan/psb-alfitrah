<?php

namespace App\Models\Core;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkoutStudent extends Model
{
    protected $fillable = [
        'student_id',
        'reason'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
