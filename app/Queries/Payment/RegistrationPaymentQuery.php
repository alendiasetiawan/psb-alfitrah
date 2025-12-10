<?php

namespace App\Queries\Payment;

use App\Enums\VerificationStatusEnum;
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
            ->addSelect('students.id', 'students.name as student_name', 'students.gender', 'students.country_code', 'students.mobile_phone', 'students.created_at as registration_date')
            ->orderBy('students.id', 'desc');
    }

    public static function countTotalPaymentNotStarted($admissionId)
    {
        return RegistrationPayment::NotPaid()
            ->join('students', 'registration_payments.student_id', 'students.id')
            ->where('students.admission_id', $admissionId)
            ->count();
    }

    public static function countTotalPaymentProcess($admissionId)
    {
        return RegistrationPayment::Process()
            ->join('students', 'registration_payments.student_id', 'students.id')
            ->where('students.admission_id', $admissionId)
            ->count();
    }

    public static function sumIncomeRegistrationPayment($admissionId)
    {
        $query = RegistrationPayment::Paid()
            ->join('students', 'registration_payments.student_id', 'students.id')
            ->select('students.admission_id', 'registration_payments.amount', 'registration_payments.student_id')
            ->where('students.admission_id', $admissionId);

        return collect([
            'sumPayment' => $query->sum('registration_payments.amount'),
            'totalStudent' => $query->count('registration_payments.student_id')
        ]);
    }

    public static function paginatePaidStudent($admissionId, $searchStudent = null, $limitData)
    {
        return RegistrationPaymentQuery::queryPaymentVerification($admissionId, $searchStudent)
            ->with([
                'registrationInvoices' => function ($query) {
                    $query->select('id', 'student_id', 'amount', 'paid_at', 'payment_method')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                }
            ])
            ->where('registration_payments.payment_status', VerificationStatusEnum::VALID)
            ->paginate($limitData);
    }
}
