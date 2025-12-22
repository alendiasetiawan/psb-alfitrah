<?php

namespace App\Queries\AdmissionData;

use App\Enums\PlacementTestEnum;
use App\Models\AdmissionData\Student;
use App\Models\Core\Branch;
use Illuminate\Support\Facades\DB;

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
                        ->select('parents.*', 'users.username', 'users.photo as user_photo')
                        ->with([
                            'educationFather:id,name',
                            'jobFather:id,name',
                            'sallaryFather:id,name',
                            'educationMother:id,name',
                            'jobMother:id,name',
                            'sallaryMother:id,name',
                            'educationGuardian:id,name',
                            'jobGuardian:id,name',
                            'sallaryGuardian:id,name'
                        ]);;
                }
            ])
            ->where('students.id', $studentId)
            ->first();
    }

    public static function fetchStudentAttachmentWithStatus($studentId)
    {
        return Student::baseEloquent($studentId)
            ->join('admission_verifications', 'students.id', 'admission_verifications.student_id')
            ->join('admissions', 'students.admission_id', 'admissions.id')
            ->joinBranchAndProgram()
            ->addSelect('students.id', 'students.name as student_name', 'students.country_code', 'students.mobile_phone', 'registration_payment', 'attachment', 'attachment_error_msg', 'admissions.name as academic_year', 'students.reg_number')
            ->with('studentAttachment')
            ->first();
    }

    public static function fetchStudentPresenceTest($studentId)
    {
        return Student::join('admission_verifications', 'students.id', 'admission_verifications.student_id')
            ->joinBranchAndProgram()
            ->addSelect('students.id', 'students.name as student_name', 'students.reg_number', 'registration_payment', 'biodata', 'attachment')
            ->with([
                'testQrCode',
                'placementTestPresence'
            ])
            ->where('students.id', $studentId)
            ->first();
    }

    public static function fetchAnnouncementTestResult($studentId)
    {
        return Student::baseEloquent($studentId)
            ->joinAdmissionVerification()
            ->joinBranchAndProgram()
            ->joinAdmission()
            ->joinRegistrationPayment()
            ->addSelect('students.name as student_name', 'students.gender', 'students.id', 'students.reg_number')
            ->with([
                'placementTestResult' => function ($query) {
                    $query->select('student_id',  'final_score', 'final_result', 'publication_status',)
                        ->where('publication_status', PlacementTestEnum::PUBLICATION_RELEASE);
                },
                'placementTestPresence'
            ])
            ->first();
    }

    public static function fetchAnnouncementStudent($studentId)
    {
        return Student::baseEloquent($studentId)
            ->joinAdmissionVerification()
            ->addSelect('students.name as student_name', 'students.gender', 'students.id')
            ->first();
    }

    public static function paginateStudentRegistrant($searchStudent = null, $selectedAdmissionId, $limitData)
    {
        return Student::baseEloquent(
            searchStudent: $searchStudent,
            admissionId: $selectedAdmissionId
        )
            ->joinBranchAndProgram()
            ->joinRegistrationPayment()
            ->joinUser()
            ->addSelect('students.id', 'students.name as student_name', 'students.gender', 'students.reg_number', 'students.parent_id', 'students.created_at as registration_date', 'students.country_code', 'students.mobile_phone')
            ->orderBy('students.id', 'desc')
            ->paginate($limitData);
    }

    public static function countStudentRegistrant($selectedAdmissionId)
    {
        return DB::table('students')
            ->where('admission_id', $selectedAdmissionId)
            ->count();
    }

    public static function fetchDetailRegistrant($studentId)
    {
        return Student::baseEloquent(
            studentId: $studentId
        )
            ->joinBranchAndProgram()
            ->joinRegistrationPayment()
            ->joinUser()
            ->joinAdmission()
            ->addSelect('students.id', 'students.name as student_name', 'students.gender', 'students.reg_number', 'students.created_at as registration_date', 'students.country_code', 'students.mobile_phone')
            ->first();
    }

    public static function paginateOfficialStudent($searchStudent = null, $selectedAdmissionId, $limitData)
    {
        return Student::baseEloquent(
            searchStudent: $searchStudent,
            admissionId: $selectedAdmissionId
        )
            ->joinBranchAndProgram()
            ->joinUser()
            ->joinPlacementTestResult()
            ->addSelect('students.id', 'students.name as student_name', 'students.gender', 'students.reg_number', 'students.parent_id', 'students.is_scholarship', 'students.nisn')
            ->orderBy('students.name', 'asc')
            ->where('students.is_walkout', false)
            ->where('final_result', PlacementTestEnum::RESULT_PASS)
            ->where('publication_status', PlacementTestEnum::PUBLICATION_RELEASE)
            ->paginate($limitData);
    }

    public static function getDownloadStudentInBranch($branchId, $admissionId)
    {
        return Student::baseEloquent(
            admissionId: $admissionId,
            branchId: $branchId
        )
            ->joinBranchAndProgram()
            ->joinPlacementTestResult()
            ->joinAdmission()
            ->joinDemografi()
            ->joinStudentAttachment()
            ->addSelect('students.*')
            ->with([
                'parent' => function ($query) {
                    $query->with([
                        'educationFather:id,name',
                        'jobFather:id,name',
                        'sallaryFather:id,name',
                        'educationMother:id,name',
                        'jobMother:id,name',
                        'sallaryMother:id,name',
                        'educationGuardian:id,name',
                        'jobGuardian:id,name',
                        'sallaryGuardian:id,name'
                    ]);
                }
            ])
            ->orderBy('students.name', 'asc')
            ->where('students.is_walkout', false)
            ->where('final_result', PlacementTestEnum::RESULT_PASS)
            ->where('publication_status', PlacementTestEnum::PUBLICATION_RELEASE)
            ->get();
    }

    public static function fetchStudentDetailWithAttachment($studentId)
    {
        return Student::baseEloquent(
            studentId: $studentId
        )
            ->joinBranchAndProgram()
            ->joinAdmission()
            ->joinDemografi()
            ->joinStudentAttachment()
            ->addSelect('students.*')
            ->with([
                'parent' => function ($query) {
                    $query->with([
                        'educationFather:id,name',
                        'jobFather:id,name',
                        'sallaryFather:id,name',
                        'educationMother:id,name',
                        'jobMother:id,name',
                        'sallaryMother:id,name',
                        'educationGuardian:id,name',
                        'jobGuardian:id,name',
                        'sallaryGuardian:id,name'
                    ]);
                }
            ])
            ->first();
    }

    public static function queryBiodataVerification($admissionId, $searchStudent = null)
    {
        return Student::baseEloquent(
            admissionId: $admissionId,
            searchStudent: $searchStudent
        )
            ->joinBranchAndProgram()
            ->joinAdmissionVerification()
            ->joinUser()
            ->addSelect('students.name as student_name', 'students.gender', 'students.id', 'students.created_at as registration_date', 'students.country_code', 'students.mobile_phone', 'students.modified_at', 'students.old_school_name', 'students.nisn');
    }

    public static function queryAttachmentVerification($admissionId, $searchStudent = null)
    {
        return Student::baseEloquent(
            admissionId: $admissionId,
            searchStudent: $searchStudent
        )
            ->joinBranchAndProgram()
            ->joinAdmissionVerification()
            ->joinUser()
            ->joinStudentAttachment()
            ->addSelect('students.name as student_name', 'students.gender', 'students.id', 'students.created_at as registration_date', 'students.country_code', 'students.mobile_phone', 'students.modified_at', 'students.old_school_name', 'students.nisn');
    }
}
