<?php

namespace App\Queries\Payment;

use App\Models\AdmissionData\Student;

class RegistrationInvoiceQuery
{
    public static function fetchLatestPayment($studentId) {
        return Student::baseEloquent($studentId)
        ->joinRegistrationPayment()
        ->joinBranchAndProgram()
        ->addSelect('students.name as student_name', 'students.gender', 'students.id')
        ->with([
            'registrationInvoices' => function ($query) {
                $query->orderBy('id', 'desc')
                ->limit(1);
            }
        ])
        ->where('students.id', $studentId)
        ->first();
    }
}