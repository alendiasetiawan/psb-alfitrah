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

    //Scope for get admission status and payment status
    public function scopeJoinVerificationRegistrationPayment($query)
    {
        return $query->join('admission_verifications', 'registration_invoices.student_id', 'admission_verifications.student_id')
        ->join('registration_payments', 'registration_invoices.student_id', 'registration_payments.student_id')
        ->addSelect('admission_verifications.registration_payment', 'registration_payments.payment_status');
    }

    public static function baseEloquent($studentId = null, $paymentStatus = null, $externalId = null) {
        return RegistrationInvoice::when(!empty($studentId), function ($query) use ($studentId) {
            return $query->where('student_id', $studentId);
        })
        ->when(!empty($paymentStatus), function ($query) use ($paymentStatus) {
            return $query->where('status', $paymentStatus);
        })
        ->when(!empty($externalId), function ($query) use ($externalId) {
            return $query->where('external_id', $externalId);
        });
    }
}
