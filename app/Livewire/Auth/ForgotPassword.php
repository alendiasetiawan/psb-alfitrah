<?php

namespace App\Livewire\Auth;

use App\Helpers\CodeGeneratorHelper;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\Core\ResetPasswordRequest;
use App\Models\User;
use App\Validation\UserValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Lupa Password')]
class ForgotPassword extends Component
{
    public ?int $otpCode = null;
    public array $inputs = [
        'username' => ''
    ];

    protected function rules(): array
    {
        return UserValidation::activeUserCheck();
    }

    protected function messages(): array
    {
        return UserValidation::messages();
    }

    //ACTION: Send OTP Code to user
    public function sendOtpReset(): void
    {
        $this->validate();
        $this->inputs['username'] = ltrim($this->inputs['username'], '0');

        //Check if user exist
        $isUserExist = User::where('username', $this->inputs['username'])->exists();

        if (!$isUserExist) {
            session()->flash('error-user-not-found', 'Nomor anda tidak terdaftar, pastikan nomor yang anda masukan sudah benar');
            return;
        }

        try {
            //Fetch user data
            $user = User::where('username', $this->inputs['username'])->first();

            //Create otp request for user
            $resetToken = Str::random(20);
            $otpCode = CodeGeneratorHelper::otpCode();
            $mobilePhone = $user->mobile_phone;

            DB::transaction(function () use ($user, $resetToken, $otpCode, $mobilePhone) {
                ResetPasswordRequest::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'is_reset_used' => false,
                ],
                [
                    'otp_code' => $otpCode,
                    'otp_expired_at' => CodeGeneratorHelper::otpExpiredOnMinute(),
                    'reset_token' => $resetToken,
                ]);

                //Send OTP to user
                $message = MessageHelper::waOtpResetPassword($otpCode);
                WhaCenterHelper::sendText($mobilePhone, $message);
            });
  
            session()->flash('success-send-otp');
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-send-otp', 'Upss.. Gagal mengirim OTP, silahkan coba beberapa saat lagi');
        }
    }

    //ACTION - Resend OTP when user click resend button
    public function resendOtp()
    {
        try {
            //Fetch user data
            $this->inputs['username'] = ltrim($this->inputs['username'], '0');
            $user = User::where('username', $this->inputs['username'])->first();
            $otp = CodeGeneratorHelper::otpCode();

            //Update otp request for user
            ResetPasswordRequest::where('user_id', $user->id)
                ->update([
                    'otp_code' => $otp,
                    'otp_expired_at' => CodeGeneratorHelper::otpExpiredOnMinute()
                ]);

            $waNumber = $user->mobile_phone;

            //Resend OTP to student
            $resendOtpMessage = MessageHelper::waResendOtp($otp);
            WhaCenterHelper::sendText($waNumber, $resendOtpMessage);

            session()->flash('success-send-otp');
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('success-send-otp');
            session()->flash('otp-failed', 'Ups... Terjadi kesalahan, silahkan coba lagi');
        }
    }

    public function verifyOtp() 
    {
        //Check if otp is valid
        $isOtpValid = ResetPasswordRequest::isOtpValid($this->otpCode);

        if (!$isOtpValid) {
            session()->flash('success-send-otp');
            session()->flash('otp-not-valid', 'Ups... Kode OTP yang anda masukan tidak valid');
            return;
        }

        //Redirect user
        $reset = ResetPasswordRequest::where('otp_code', $this->otpCode)->first();
        $this->redirect(route('password.reset', ['token' => $reset->reset_token]));
    }
}
