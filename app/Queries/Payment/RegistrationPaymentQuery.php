<?php

namespace App\Queries\Payment;

use App\Models\AdmissionData\RegistrationPayment;

class RegistrationPaymentQuery
{
    public static function fetchStudentPayment($studentId) {
        return RegistrationPayment::where('student_id', $studentId)->first();
    }
}