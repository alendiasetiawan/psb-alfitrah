<?php

namespace App\Models\AdmissionData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationPayment extends Model
{
    protected $fillable = [
        'student_id',
        'amount',
        'evidence',
        'payment_status', //enum('Proses','Belum','Valid','Tidak Valid','Expired')
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
