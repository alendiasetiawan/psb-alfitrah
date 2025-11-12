<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FormValidatorHelper
{
    public static function validate(array $data, array $rules, array $messages = [])
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'min' => ':attribute minimal :min karakter.',
            'email' => 'Format :attribute tidak valid.',
            'same' => 'Konfirmasi :attribute harus sama.',
        ];
    }
}
