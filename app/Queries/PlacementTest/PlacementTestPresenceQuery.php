<?php

namespace App\Queries\PlacementTest;

use App\Enums\PaymentStatusEnum;
use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\Student;
use App\Models\PlacementTest\PlacementTestPresence;

class PlacementTestPresenceQuery
{
    public static function paginatePresenceStudents($admissionId, $limitData)
    {
        $studentLists = PlacementTestPresence::with([
            'student' => function ($query) use ($admissionId) {
                $query->addSelect('students.id', 'gender', 'students.name as student_name', 'students.admission_id', 'students.mobile_phone', 'students.country_code', 'admission_batches.name as batch_name')
                ->where('students.admission_id', $admissionId)
                ->where('admission_verifications.registration_payment', VerificationStatusEnum::VALID)
                ->joinBranchAndProgram()
                ->join('admission_batches', 'students.admission_batch_id', 'admission_batches.id')
                ->joinAdmissionVerification();
            }
        ])
        ->orderBy('id', 'desc')
        ->cursorPaginate($limitData);

        $totalAbsence = self::totalAbsenceStudents($admissionId);

        $totalPresence = self::totalPresenceStudents($admissionId);

        return collect([
            'studentLists' => $studentLists,
            'totalAbsence' => $totalAbsence,
            'totalPresence' => $totalPresence
        ]);
    }   

    public static function totalAbsenceStudents($admissionId)
    {
        return Student::joinAdmissionVerification()
        ->where('admission_verifications.registration_payment', VerificationStatusEnum::VALID)
        ->where('admission_id', $admissionId)
        ->whereDoesntHave('placementTestPresence')
        ->count();
    }

    public static function totalPresenceStudents($admissionId)
    {
        return PlacementTestPresence::join('students', 'placement_test_presences.student_id', 'students.id')
        ->where('students.admission_id', $admissionId)
        ->count();
    }
}