<?php

namespace App\Queries\Payment;

use App\Models\AdmissionData\RegistrationPayment;
use App\Models\AdmissionData\Student;

class RegistrationPaymentQuery
{
    public static function fetchStudentPayment($studentId)
    {
        return RegistrationPayment::where('student_id', $studentId)->first();
    }

    public static function queryPaymentVerification($admissionId, $searchStudent = null)
    {
        return Student::baseEloquent(
            searchStudent: $searchStudent,
            admissionId: $admissionId,
        )
            ->joinUser()
            ->joinAdmissionVerification()
            ->joinBranchAndProgram()
            ->joinRegistrationPayment()
            ->addSelect('students.id', 'students.name as student_name', 'students.country_code', 'students.mobile_phone', 'students.created_at as registration_date')
            ->orderBy('students.id', 'desc');
    }

    public static function countTotalPaymentNotStarted($admissionId)
    {
        return RegistrationPayment::NotPaid()
            ->join('students', 'registration_payments.student_id', 'students.id')
            ->where('students.admission_id', $admissionId)
            ->count();
    }
}
