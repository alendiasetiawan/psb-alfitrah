<?php

namespace App\Models\Payment;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationInvoice extends Model
{
    protected $fillable = [
        'username',
        'student_id',
        'invoice_id',
        'external_id',
        'amount',
        'description',
        'expiry_date',
        'payment_url',
        'paid_at',
        'raw_callback',
        'payment_method',
        'status'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
