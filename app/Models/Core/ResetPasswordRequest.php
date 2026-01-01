<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ResetPasswordRequest extends Model
{
    protected $fillable = [
        'user_id',
        'otp_code',
        'otp_expired_at',
        'reset_token',
        'is_reset_used'
    ];

    public static function isOtpValid($otpCode)
    {
        return self::where('otp_code', $otpCode)->where('otp_expired_at', '>=', now())->exists();
    }

    public static function fetchValidToken($token)
    {
        return self::where('reset_token', $token)->where('is_reset_used', false)->first();
    }

    public static function isTokenValid($token)
    {
        return self::where('reset_token', $token)->where('is_reset_used', false)->exists();
    }
}
