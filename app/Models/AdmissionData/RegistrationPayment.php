<?php

namespace App\Models\AdmissionData;

use App\Enums\PaymentStatusEnum;
use App\Enums\VerificationStatusEnum;
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

    //Scope for status PAID
    public function scopePaid($query)
    {
        return $query->where('payment_status', VerificationStatusEnum::VALID);
    }

    //Scope for status Not PAID
    public function scopeNotPaid($query)
    {
        return $query->where('payment_status', VerificationStatusEnum::NOT_STARTED);
    }

    //Scope for status Process
    public function scopeProcess($query)
    {
        return $query->whereIn('payment_status', [VerificationStatusEnum::PROCESS, PaymentStatusEnum::EXPIRED]);
    }
}
