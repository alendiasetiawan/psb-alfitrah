<?php

namespace App\Queries\PlacementTest;

use App\Enums\OrderDataEnum;
use App\Enums\PlacementTestEnum;
use App\Models\AdmissionData\Student;
use App\Models\PlacementTest\PlacementTestResult;

class PlacementTestResultQuery
{
   public static function paginateStudentTestResults($limitData, $orderBy = null, $admissionId = null, $admissionBatchId = null, $searchStudent = null, $branchId = null)
   {
        return Student::baseEloquent(
            admissionId: $admissionId,
            admissionBatchId: $admissionBatchId,
            searchStudent: $searchStudent,
            branchId: $branchId,
        )
        ->joinAdmissionBatch()
        ->joinBranchAndProgram()
        ->joinPlacementTestResult()
        ->joinUser()
        ->addSelect('students.name as student_name', 'students.id')
        ->when($orderBy, function ($query) use ($orderBy) {
            if ($orderBy == OrderDataEnum::PUBLICATION) {
                return $query->orderBy('placement_test_results.publication_status', 'asc');
            }

            if ($orderBy == OrderDataEnum::FINAL_SCORE) {
                return $query->orderBy('placement_test_results.final_score', 'desc');
            }
        })
        ->paginate($limitData);
   }


   public static function countTotalResult($admissionId = null, $admissionBatchId = null, $branchId = null)
   {
        $query = PlacementTestResult::join('students', 'placement_test_results.student_id', 'students.id')
        ->when($admissionId, function ($query) use ($admissionId) {
            return $query->where('students.admission_id', $admissionId);
        })
        ->when($admissionBatchId, function ($query) use ($admissionBatchId) {
            return $query->where('students.admission_batch_id', $admissionBatchId);
        })
        ->when($branchId, function ($query) use ($branchId) {
            return $query->where('students.branch_id', $branchId);
        })
        ->selectRaw('final_result, COUNT(*) as total')
        ->groupBy('final_result')
        ->pluck('total', 'final_result');

        $totalPass = $query[PlacementTestEnum::RESULT_PASS] ?? 0;
        $totalFail = $query[PlacementTestEnum::RESULT_FAIL] ?? 0;

        return [
            'totalPass' => $totalPass,
            'totalFail' => $totalFail,
        ];
   }

   public static function fetchStudentPublicationStatus($testId)
   {
        return PlacementTestResult::joinStudent()
        ->where('placement_test_results.id', $testId)
        ->addSelect('placement_test_results.publication_status', 'placement_test_results.student_id')
        ->first();
   }

   public static function fetchStudentDetailTest($studentId)
   {
        return Student::joinPlacementTestResult()
        ->joinBranchAndProgram()
        ->joinAdmission()
        ->joinAdmissionBatch()
        ->joinPlacementTestPresence()
        ->addSelect('students.country_code', 'students.mobile_phone' , 'students.name as student_name', 'students.id')
        ->where('students.id', $studentId)
        ->first();
   }

   public static function getHoldStudents()
   {
        return Student::baseEloquent()
        ->joinAdmission()
        ->joinBranchAndProgram()
        ->joinPlacementTestResult()
        ->where('placement_test_results.publication_status', PlacementTestEnum::PUBLICATION_HOLD)
        ->where('placement_test_results.final_result', '!=', PlacementTestEnum::RESULT_WAITING)
        ->addSelect('students.id', 'students.country_code', 'students.mobile_phone', 'students.name as student_name')
        ->orderBy('students.name', 'asc')
        ->get();
   }
}