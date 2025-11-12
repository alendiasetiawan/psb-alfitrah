<?php

namespace App\Queries\Core;

use App\Models\User;

class UserQuery {

    public static function findUserOtp($otp) {
        return User::where('otp', $otp)
        ->where('is_verified', false)
        ->first();
    }

    public static function isOtpValid($otp) {
        return User::where('otp', $otp)
        ->where('is_verified', false)
        ->exists();
    }

    public static function isOtpExpired($otp) {
        return User::where('otp', $otp)
        ->where('is_verified', false)
        ->where('otp_expired_at', '<', now())
        ->exists();
    }

    public static function fetchStudentAccount($username) {
        return User::join('students', 'users.id', 'students.user_id')
        ->select('users.id', 'users.username', 'students.country_code', 'students.mobile_phone')
        ->where('users.username', $username)
        ->first();
    }
}