<?php

namespace App\Models\PlacementTest;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestQrCode extends Model
{
    protected $fillable = [
        'student_id',
        'qr',
        'expired_at'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
