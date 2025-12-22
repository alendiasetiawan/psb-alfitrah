<?php

namespace App\Services;

use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\MultiStudent;
use App\Models\AdmissionData\Student;
use App\Queries\AdmissionData\StudentQuery;

class StudentDataService
{
    public function findActiveStudentId($parentId)
    {
        $data = MultiStudent::where('parent_id', $parentId)
            ->first();

        return $data->student_id;
    }

    public function isMultiStudent($userId)
    {
        $countStudent = Student::where('user_id', $userId)
            ->count();

        return $countStudent > 1 ? true : false;
    }

    public function paginateStudentPendingBiodata($admissionId, $searchStudent = null, $limitData)
    {
        return StudentQuery::queryBiodataVerification($admissionId, $searchStudent)
            ->where('admission_verifications.biodata', VerificationStatusEnum::NOT_STARTED)
            ->orderBy('students.id', 'asc')
            ->paginate($limitData);
    }

    public function paginateStudentProcessBiodata($admissionId, $searchStudent = null, $limitData)
    {
        return StudentQuery::queryBiodataVerification($admissionId, $searchStudent)
            ->whereIn('admission_verifications.biodata', [VerificationStatusEnum::PROCESS, VerificationStatusEnum::INVALID])
            ->orderBy('students.id', 'asc')
            ->paginate($limitData);
    }

    public function paginateStudentVerifiedBiodata($admissionId, $searchStudent = null, $limitData)
    {
        return StudentQuery::queryBiodataVerification($admissionId, $searchStudent)
            ->where('admission_verifications.biodata', VerificationStatusEnum::VALID)
            ->orderBy('students.id', 'asc')
            ->paginate($limitData);
    }

    public function paginateStudentPendingAttachment($admissionId, $searchStudent = null, $limitData)
    {
        return StudentQuery::queryBiodataVerification($admissionId, $searchStudent)
            ->where('admission_verifications.attachment', VerificationStatusEnum::NOT_STARTED)
            ->orderBy('students.id', 'asc')
            ->paginate($limitData);
    }

    public function paginateStudentProcessAttachment($admissionId, $searchStudent = null, $limitData)
    {
        return StudentQuery::queryAttachmentVerification($admissionId, $searchStudent)
            ->whereIn('admission_verifications.attachment', [VerificationStatusEnum::PROCESS, VerificationStatusEnum::INVALID])
            ->orderBy('students.id', 'asc')
            ->paginate($limitData);
    }

    public function paginateStudentVerifiedAttachment($admissionId, $searchStudent = null, $limitData)
    {
        return StudentQuery::queryAttachmentVerification($admissionId, $searchStudent)
            ->where('admission_verifications.attachment', VerificationStatusEnum::VALID)
            ->orderBy('students.id', 'asc')
            ->paginate($limitData);
    }
}
