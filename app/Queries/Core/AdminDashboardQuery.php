<?php

namespace App\Queries\Core;

use App\Enums\PlacementTestEnum;
use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\Student;
use App\Models\Core\EducationProgram;

class AdminDashboardQuery
{
    public static function counterStatisticSummary($admissionId)
    {
        return Student::leftJoin('placement_test_results', 'students.id', 'placement_test_results.student_id')  
            ->selectRaw('COUNT(students.id) as total_registrant')
            ->selectRaw("COUNT(CASE WHEN placement_test_results.final_result = '" . PlacementTestEnum::RESULT_PASS . "' THEN 1 END) as total_student_pass")
            ->join('registration_payments', 'students.id', 'registration_payments.student_id')
            ->selectRaw("SUM(CASE WHEN registration_payments.payment_status = '" . VerificationStatusEnum::VALID . "' THEN registration_payments.amount ELSE 0 END) as total_payment")
            ->where('admission_id', $admissionId)
            ->first();
    }

    public static function chartRegistrantPerProgram($admissionId)
    {
        return EducationProgram::with([
            'students' => function ($query) use ($admissionId) {
                $query->select('id', 'education_program_id', 'admission_id')
                ->where('admission_id', $admissionId);
            }
        ])
        ->select('id', 'name as program_name')
        ->get();
    }

    public static function comparePaymentSuccess($admissionId)
    {
        return Student::join('registration_payments', 'students.id', 'registration_payments.student_id')
            ->selectRaw("COUNT(students.id) as total_registrant")
            ->selectRaw("COUNT(CASE WHEN registration_payments.payment_status = '" . VerificationStatusEnum::VALID . "' THEN 1 END) as total_payment_success")
            ->where('admission_id', $admissionId)
            ->first();
    }

    public static function countBiodataStatus($admissionId)
    {
        return Student::join('admission_verifications', 'students.id', 'admission_verifications.student_id')
            ->selectRaw("COUNT(CASE WHEN admission_verifications.biodata = '" . VerificationStatusEnum::VALID . "' THEN 1 END) as total_valid")
            ->selectRaw("COUNT(CASE WHEN admission_verifications.biodata = '" . VerificationStatusEnum::INVALID . "' THEN 1 END) as total_invalid")
            ->selectRaw("COUNT(CASE WHEN admission_verifications.biodata = '" . VerificationStatusEnum::PROCESS . "' THEN 1 END) as total_process")
            ->selectRaw("COUNT(CASE WHEN admission_verifications.biodata = '" . VerificationStatusEnum::NOT_STARTED . "' THEN 1 END) as total_pending")
            ->where('admission_id', $admissionId)
            ->first();
    }

    public static function countStudentAttachmentStatus($admissionId)
    {
        return Student::join('admission_verifications', 'students.id', 'admission_verifications.student_id')
            ->selectRaw("COUNT(CASE WHEN admission_verifications.attachment = '" . VerificationStatusEnum::VALID . "' THEN 1 END) as total_valid")
            ->selectRaw("COUNT(CASE WHEN admission_verifications.attachment = '" . VerificationStatusEnum::INVALID . "' THEN 1 END) as total_invalid")
            ->selectRaw("COUNT(CASE WHEN admission_verifications.attachment = '" . VerificationStatusEnum::PROCESS . "' THEN 1 END) as total_process")
            ->selectRaw("COUNT(CASE WHEN admission_verifications.attachment = '" . VerificationStatusEnum::NOT_STARTED . "' THEN 1 END) as total_pending")
            ->where('admission_id', $admissionId)
            ->first();
    }

    public static function latestRegistrant($admissionId, int $limit = 3)
    {
        return Student::joinBranchAndProgram()
            ->where('students.admission_id', $admissionId)
            ->addSelect('students.name as student_name', 'students.created_at', 'students.id')
            ->orderBy('students.id', 'desc')
            ->limit($limit)
            ->get();
    }
}