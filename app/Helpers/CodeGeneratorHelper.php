<?php

namespace App\Helpers;

use App\Models\AdmissionData\Student;
use Carbon\Carbon;

class CodeGeneratorHelper {

    public static function otpCode() {
        return mt_rand(100000, 999999);
    }

    public static function otpExpiredOnMinute($minute = 10) {
        return Carbon::now()->addMinutes($minute);
    }

    public static function studentRegNumber($admissionName, $admissionId) {
        //Check how many student registered on active admission
        $studentRegistered = Student::where('admission_id', $admissionId)->count();
        $studentNumber = $studentRegistered + 1;

        //Get 2 last digit of admission name
        list($start, $end) = explode('-', $admissionName);
        $result = substr($start, 2) . substr($end, 2);

        //Get month and date today
        $month = Carbon::now()->format('m');
        $date = Carbon::now()->format('d');

        return $result . '-' . $month . $date . '-' . '0'. $studentNumber;
    }

    public static function registrationInvoiceNumber($admissionName) {
        list($start, $end) = explode('-', $admissionName);
        $lastDigitAdmission = substr($start, 2) . substr($end, 2);

        return 'INV-PSB' .$lastDigitAdmission. '-'. now()->timestamp . '-' . rand(1000, 9999);
    }
}