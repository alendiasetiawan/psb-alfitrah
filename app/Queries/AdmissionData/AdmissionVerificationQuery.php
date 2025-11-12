<?php

namespace App\Queries\AdmissionData;

use Illuminate\Support\Facades\DB;

class AdmissionVerificationQuery {

    public static function fetchStudentStatus($studentId) {
        return DB::table('admission_verifications')
        ->where('student_id', $studentId)
        ->select('id', 'registration_payment', 'biodata', 'attachment', 'placement_test', 'payment_error_msg', 'biodata_error_msg', 'attachment_error_msg')
        ->first();
    }
}