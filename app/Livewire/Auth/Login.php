<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use App\Const\RoleConst;
use Illuminate\Support\Str;
use App\Helpers\MessageHelper;
use Livewire\Attributes\Title;
use App\Queries\Core\UserQuery;
use Livewire\Attributes\Layout;
use App\Helpers\WhaCenterHelper;
use Livewire\Attributes\Validate;
use Illuminate\Auth\Events\Lockout;
use App\Helpers\CodeGeneratorHelper;
use App\Services\StudentDataService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.auth')]
#[Title('Login Aplikasi')]
class Login extends Component
{
   public string $username = '';
   public string $password = '';
   public bool $remember = false;
   public $inputs = [
      'otp'
   ];

   protected $rules = [
      'username' => 'required|exists:users,username',
      'password' => 'required',
   ];

   protected $messages = [
      'username.required' => 'Username wajib diisi.',
      'username.exists' => 'Username tidak ditemukan.',
      'password.required' => 'Password wajib diisi.',
   ];

   public function mount()
   {
      if (Cookie::get('saveuser') && Cookie::get('savepwd')) {
         $this->username = Cookie::get('saveuser');
         $this->password = Cookie::get('savepwd');
         $this->remember = true;
      }
   }

   public function login()
   {
      $this->username = ltrim($this->username, '0');

      $this->validate();

      $this->ensureIsNotRateLimited();

      if (! Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
         RateLimiter::hit($this->throttleKey());

         throw ValidationException::withMessages([
            'password' => "Password salah",
         ]);
      }

      RateLimiter::clear($this->throttleKey());

      //Check if user already verified
      $checkUnVerified = User::where('username', $this->username)
         ->where('is_verified', 0)
         ->exists();

      if ($checkUnVerified) {
         session()->flash('user-unverified', 'Gagal login, silahkan verifikasi akun anda terlebih dahulu!');
      } else {
         if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            if ($this->remember) {
               Cookie::queue('saveuser', $this->username, 20160);
               Cookie::queue('savepwd', $this->password, 20160);
            }
            
            $userData = Auth::user();
            $userCheck = Auth::check();
            session([
               'userData' => $userData,
               'userCheck' => $userCheck
            ]);

            $this->redirect(route('login'), navigate: true);
         } else {
            $this->addError('password', 'Password yang anda masukan salah, silahkan coba lagi!');
         }
      }
   }

   /**
    * Ensure the authentication request is not rate limited.
    */
   protected function ensureIsNotRateLimited(): void
   {
      if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
         return;
      }

      event(new Lockout(request()));

      $seconds = RateLimiter::availableIn($this->throttleKey());

      throw ValidationException::withMessages([
         'username' => __('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
         ]),
      ]);
   }

   /**
    * Get the authentication rate limiting throttle key.
    */
   protected function throttleKey(): string
   {
      return Str::transliterate(Str::lower($this->username) . '|' . request()->ip());
   }


   //ACTION - Resend OTP when user click resend button
   public function resendOtp()
   {
      Session::flush();
      try {
         //Setup new OTP for user
         $otp = CodeGeneratorHelper::otpCode();
         User::where('username', $this->username)
            ->update([
               'otp' => $otp,
               'otp_expired_at' => CodeGeneratorHelper::otpExpiredOnMinute()
            ]);

         //Find student account
         $user = UserQuery::fetchStudentAccount($this->username);
         $waNumber = $user->country_code . $user->mobile_phone;

         //Resend OTP to student
         $resendOtpMessage = MessageHelper::waResendOtp($otp);
         $send = WhaCenterHelper::sendText($waNumber, $resendOtpMessage);

         // dd($send);

         session()->flash('resend-otp-success', 'Kode telah dikirim, silahkan cek aplikasi Whatsapp anda!');
         $this->redirect(route('login'));
      } catch (\Throwable $th) {
         logger($th);
         session()->flash('resend-otp-failed', 'Ups... Terjadi kesalahan, silahkan coba lagi');
      }
   }

   //ACTION - OTP verification after user successfully registered
   public function otpVerification()
   {
      $this->validate([
         'inputs.otp' => 'required'
      ], [
         'inputs.otp.required' => 'Kode OTP harus diisi!'
      ]);

      //Check if OTP is valid and not verified
      $isOtpValid = UserQuery::isOtpValid($this->inputs['otp']);
      if (!$isOtpValid) {
         return session()->flash('otp-failed', 'Kode OTP salah, silahkan coba lagi!');
      }

      //Check if OTP is expired
      $isOtpExpired = UserQuery::isOtpExpired($this->inputs['otp']);
      if ($isOtpExpired) {
         return session()->flash('otp-failed', 'Kode OTP sudah tidak berlaku, silahkan request lagi!');
      }

      try {
         //Update student's verification status
         $findUserOtp = UserQuery::findUserOtp($this->inputs['otp']);
         $findUserOtp->update([
            'is_verified' => true,
            'verified_at' => now()
         ]);

         session()->flash('otp-success', 'Verifikasi akun berhasil, silahkan login!');
         $this->redirect(route('login'));
      } catch (\Throwable $th) {
         logger($th);
         session()->flash('otp-failed', 'Ups... Terjadi kesalahan, silahkan coba lagi');
      }
   }
}
