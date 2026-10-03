<?php

namespace App\Queries\Payment;

use App\Enums\PaymentStatusEnum;
use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\RegistrationPayment;
use App\Models\AdmissionData\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class RegistrationPaymentQuery
{
    public static function fetchStudentPayment(int $studentId): ?RegistrationPayment
    {
        return RegistrationPayment::baseEloquent($studentId)->first();
    }

    public static function fetchStudentPaymentDetails(int $studentId, int $userId): Student
    {
        return Student::baseEloquent(studentId: $studentId)
            ->where('students.user_id', $userId)
            ->joinRegistrationPayment()
            ->joinBranchAndProgram()
            ->joinAdmission()
            ->addSelect('students.id', 'students.name as student_name')
            ->with('admissionVerification')
            ->firstOrFail();
    }

    public static function fetchPaymentForUpdate(int $studentId, ?int $userId = null): RegistrationPayment
    {
        return RegistrationPayment::baseEloquent($studentId)
            ->when($userId !== null, fn (Builder $query) => $query->whereHas('student', fn (Builder $student) => $student->where('user_id', $userId)))
            ->with('student.admissionVerification')
            ->lockForUpdate()
            ->firstOrFail();
    }

    public static function fetchPaymentForVerification(int $studentId, int $admissionId): RegistrationPayment
    {
        return RegistrationPayment::baseEloquent($studentId)
            ->whereHas('student', fn (Builder $query) => $query->where('admission_id', $admissionId))
            ->with('student.admissionVerification')
            ->firstOrFail();
    }

    public static function paginateProcessStudent(int $admissionId, ?string $searchStudent, int $limitData): LengthAwarePaginator
    {
        return self::queryPaymentVerification($admissionId, $searchStudent)
            ->whereIn('registration_payments.payment_status', [VerificationStatusEnum::PROCESS, VerificationStatusEnum::INVALID, PaymentStatusEnum::EXPIRED])
            ->paginate($limitData);
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
            ->addSelect('students.id', 'students.name as student_name', 'students.gender', 'students.country_code', 'students.mobile_phone', 'students.created_at as registration_date', 'registration_payments.updated_at as payment_updated_at')
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
            'totalStudent' => $query->count('registration_payments.student_id'),
        ]);
    }

    public static function paginatePaidStudent($admissionId, $searchStudent, $limitData)
    {
        return RegistrationPaymentQuery::queryPaymentVerification($admissionId, $searchStudent)
            ->with([
                'registrationInvoices' => function ($query) {
                    $query->select('id', 'student_id', 'amount', 'paid_at', 'payment_method')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                },
            ])
            ->where('registration_payments.payment_status', VerificationStatusEnum::VALID)
            ->paginate($limitData);
    }
}
