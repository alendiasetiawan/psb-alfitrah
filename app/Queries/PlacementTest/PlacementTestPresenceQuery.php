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

    public static function totalAbsenceStudents($admissionId, $admissionBatchId = null)
    {
        return Student::joinAdmissionVerification()
        ->where('admission_verifications.registration_payment', VerificationStatusEnum::VALID)
        ->where('admission_id', $admissionId)
        ->when($admissionBatchId, function ($query) use ($admissionBatchId) {
            $query->where('students.admission_batch_id', $admissionBatchId);
        })
        ->whereDoesntHave('placementTestPresence')
        ->count();
    }

    public static function totalPresenceStudents($admissionId, $admissionBatchId = null)
    {
        return PlacementTestPresence::join('students', 'placement_test_presences.student_id', 'students.id')
        ->where('students.admission_id', $admissionId)
        ->when($admissionBatchId, function ($query) use ($admissionBatchId) {
            $query->where('students.admission_batch_id', $admissionBatchId);
        })
        ->count();
    }

    public static function presenceReports($searchStudent = null, $admissionId = null, $admissionBatchId = null, $limitData)
    {
        $studentLists = Student::baseEloquent(
            searchStudent: $searchStudent,
            admissionId: $admissionId,
            admissionBatchId: $admissionBatchId
        )
        ->addSelect('students.id', 'students.name as student_name', 'students.gender', 'students.admission_id', 'students.admission_batch_id', 'students.mobile_phone', 'students.country_code')
        ->joinAdmissionVerification()
        ->joinBranchAndProgram()
        ->joinAdmissionBatch()
        ->leftJoin('placement_test_presences', 'students.id', '=', 'placement_test_presences.student_id')
        ->with('placementTestPresence:student_id,check_in_time')
        ->where('admission_verifications.registration_payment', VerificationStatusEnum::VALID)
        ->orderByRaw('placement_test_presences.check_in_time IS NULL DESC')
        ->paginate($limitData);

        $totalPresence = self::totalPresenceStudents($admissionId, $admissionBatchId);
        $totalAbsence = self::totalAbsenceStudents($admissionId, $admissionBatchId);

        return collect([
            'studentLists' => $studentLists,
            'totalPresence' => $totalPresence,
            'totalAbsence' => $totalAbsence
        ]);
    }

    public static function detailStudentPresence($studentId)
    {
        return Student::baseEloquent(
            studentId: $studentId
        )
        ->joinBranchAndProgram()
        ->addSelect('students.id', 'students.name as student_name')
        ->with('placementTestPresence:student_id,check_in_time')
        ->first();
    }
}