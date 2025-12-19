<?php

namespace App\Queries\AdmissionData;

use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\AdmissionVerification;
use Illuminate\Support\Facades\DB;

class AdmissionVerificationQuery
{

    public static function fetchStudentStatus($studentId)
    {
        return DB::table('admission_verifications')
            ->where('student_id', $studentId)
            ->select('id', 'registration_payment', 'biodata', 'attachment', 'placement_test', 'payment_error_msg', 'biodata_error_msg', 'attachment_error_msg')
            ->first();
    }

    public static function countStudentPendingBiodata($admissionId)
    {
        return AdmissionVerification::biodataPending()
            ->join('students', 'admission_verifications.student_id', 'students.id')
            ->where('students.admission_id', $admissionId)
            ->count();
    }

    public static function countStudentProcessBiodata($admissionId)
    {
        return AdmissionVerification::biodataProcess()
            ->join('students', 'admission_verifications.student_id', 'students.id')
            ->where('students.admission_id', $admissionId)
            ->count();
    }

    public static function countStudentVerifiedBiodata($admissionId)
    {
        return AdmissionVerification::biodataVerified()
            ->join('students', 'admission_verifications.student_id', 'students.id')
            ->where('students.admission_id', $admissionId)
            ->count();
    }
}
