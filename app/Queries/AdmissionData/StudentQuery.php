<?php

namespace App\Queries\AdmissionData;

use App\Enums\PlacementTestEnum;
use App\Models\AdmissionData\Student;

class StudentQuery
{

   public static function countStudentPassTest($admissionId, $educationProgramId)
   {
      return Student::join('placement_test_results', 'students.id',  'placement_test_results.student_id')
         ->where('students.admission_id', $admissionId)
         ->where('students.education_program_id', $educationProgramId)
         ->where('placement_test_results.final_result', PlacementTestEnum::RESULT_PASS)
         ->where('placement_test_results.publication_status', PlacementTestEnum::PUBLICATION_RELEASE)
         ->count();
   }

   public static function fetchStudentDetailWithStatus($studentId)
   {
      return Student::join('admission_verifications', 'students.id', 'admission_verifications.student_id')
         ->join('education_programs', 'students.education_program_id', 'education_programs.id')
         ->join('branches', 'students.branch_id', 'branches.id')
         ->join('admissions', 'students.admission_id', 'admissions.id')
         ->select('students.*', 'registration_payment', 'biodata', 'biodata_error_msg', 'education_programs.name as program_name', 'branches.name as branch_name', 'admissions.name as academic_year')
         ->with([
            'parent' => function ($query) {
               $query->join('users', 'parents.user_id', 'users.id')
                  ->select('parents.*', 'users.username')
                  ->with('jobFather')
                  ->with('jobMother')
                  ->with('jobGuardian');
            }
         ])
         ->where('students.id', $studentId)
         ->first();
   }

   public static function fetchStudentAttachmentWithStatus($studentId)
   {
      return Student::join('admission_verifications', 'students.id', 'admission_verifications.student_id')
         ->join('admissions', 'students.admission_id', 'admissions.id')
         ->select('students.id', 'students.name as student_name', 'registration_payment', 'attachment', 'attachment_error_msg', 'admissions.name as academic_year')
         ->with('studentAttachment')
         ->where('students.id', $studentId)
         ->first();
   }
}
