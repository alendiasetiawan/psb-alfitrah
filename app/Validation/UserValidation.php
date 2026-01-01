<?php

namespace App\Validation;

class UserValidation
{
    public static function activeUserCheck(): array
    {
        return [
            'inputs.username' => ['required', 'min:8', 'max:15'],
        ];
    }

    public static function passwordCheck(): array
    {
        return [
            'inputs.password' => ['required', 'min:6'],
            'inputs.confirmPassword' => ['required', 'min:6', 'same:inputs.password'],
        ];
    }

    public static function messages(): array
    {
        return [
            'inputs.username.required' => 'Username wajib diisi',
            'inputs.username.min' => 'Username minimal 8 karakter',
            'inputs.username.max' => 'Username maksimal 15 karakter',
            'inputs.password.required' => 'Password wajib diisi',
            'inputs.password.min' => 'Password minimal 6 karakter',
            'inputs.confirmPassword.required' => 'Konfirmasi password wajib diisi',
            'inputs.confirmPassword.min' => 'Konfirmasi password minimal 6 karakter',
            'inputs.confirmPassword.same' => 'Konfirmasi password tidak sesuai',
        ];
    }
}