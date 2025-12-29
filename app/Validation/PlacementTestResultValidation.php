<?php

namespace App\Validation;

class PlacementTestResultValidation
{
    public static function create(): array
    {
        return [
            'inputs.psikotestScore' => ['required', 'numeric'],
            'inputs.readQuranScore' => ['required', 'numeric'],
            'inputs.parentInterview' => ['required'],
            'inputs.studentInterview' => ['required'],
            'inputs.finalScore' => ['required', 'numeric'],
            'inputs.finalResult' => ['required'],
        ];
    }

    public static function messages(): array
    {
        return [
            'inputs.psikotestScore.required' => 'Nilai psikotes wajib diisi',
            'inputs.psikotestScore.numeric' => 'Harus angka, gunakan (.) untuk menulis koma',
            'inputs.readQuranScore.required' => 'Nilai quran wajib diisi',
            'inputs.readQuranScore.numeric' => 'Harus angka, gunakan (.) untuk menulis koma',
            'inputs.parentInterview.required' => 'Interview orang tua wajib diisi',
            'inputs.studentInterview.required' => 'Interview santri wajib diisi',
            'inputs.finalScore.required' => 'Nilai akhir wajib diisi',
            'inputs.finalScore.numeric' => 'Harus angka, gunakan (.) untuk menulis koma',
            'inputs.finalResult.required' => 'Hasil akhir wajib diisi',
        ];
    }
}