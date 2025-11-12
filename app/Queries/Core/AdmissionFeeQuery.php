<?php

namespace App\Queries\Core;

use App\Models\Core\AdmissionFee;
use Illuminate\Support\Facades\DB;

class AdmissionFeeQuery {

    public static function fetchDetailFeeWithProgram($id) {
        return AdmissionFee::with([
            'admission' => function ($query) {
                $query->select('id', 'name')
                ->withTrashed();
            },
            'educationProgram' => function ($query) {
                $query->join('branches', 'education_programs.branch_id', 'branches.id')
                ->select('education_programs.id', 'education_programs.name as program_name', 'branches.name as branch_name')
                ->withTrashed();
            }
        ])
        ->where('id', $id)
        ->firstOrFail();
    }

    public static function fetchFeePerProgram($admissionId, $educationProgramId) {
        return DB::table('admission_fees')->where('admission_id', $admissionId)
        ->where('education_program_id', $educationProgramId)
        ->first();
    }
}