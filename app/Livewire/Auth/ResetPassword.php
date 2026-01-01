<?php

namespace App\Livewire\Auth;

use App\Models\Core\ResetPasswordRequest;
use App\Models\User;
use App\Validation\UserValidation;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    #[Locked]
    public string $token = '';

    #[Locked]
    public bool $isTokenValid = false;

    public array $inputs = [
        'password' => '',
        'confirmPassword' => '',
    ];

    protected function rules(): array
    {
        return UserValidation::passwordCheck();
    }

    protected function messages(): array
    {
        return UserValidation::messages();
    }

    public function mount()
    {
        //Check is token valid
        $this->isTokenValid = ResetPasswordRequest::isTokenValid($this->token);
    }

    public function setNewPassword()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                //Fetch reset request ID
                $reset = ResetPasswordRequest::fetchValidToken($this->token);
                $userId = $reset->user_id;

                //Set new password
                User::where('id', $userId)->update([
                    'password' => Hash::make($this->inputs['confirmPassword'])
                ]);

                //Set used token
                $reset->update([
                    'is_reset_used' => true
                ]);
            });

            session()->flash('success-reset-password', 'Password berhasil diubah!');
            $this->redirect(route('login'), navigate: true);
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-reset-password', 'Gagal, silahkan coba lagi!');
        }
    }

}
