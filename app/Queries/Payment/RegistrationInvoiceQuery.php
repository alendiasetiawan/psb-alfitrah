<?php

namespace App\Queries\Payment;

use App\Models\AdmissionData\Student;
use App\Models\Payment\RegistrationInvoice;

class RegistrationInvoiceQuery
{
    public static function fetchLatestPayment($studentId)
    {
        return Student::baseEloquent($studentId)
            ->joinRegistrationPayment()
            ->joinBranchAndProgram()
            ->addSelect('students.name as student_name', 'students.gender', 'students.id')
            ->with([
                'registrationInvoices' => function ($query) {
                    $query->addSelect('student_id', 'invoice_id', 'expiry_date', 'payment_url', 'paid_at', 'payment_method', 'status', 'created_at', 'amount')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                }
            ])
            ->where('students.id', $studentId)
            ->first();
    }

    public static function fetchActiveInvoice($externalId)
    {
        return RegistrationInvoice::baseEloquent(
            externalId: $externalId
        )
            ->joinVerificationRegistrationPayment()
            ->addSelect('invoice_id', 'external_id', 'expiry_date', 'payment_url', 'paid_at', 'payment_method', 'status')
            ->first();
    }
}
