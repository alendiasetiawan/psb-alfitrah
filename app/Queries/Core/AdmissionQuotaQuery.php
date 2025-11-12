<?php

namespace App\Queries\Core;

use App\Models\Core\AdmissionQuota;

class AdmissionQuotaQuery {

    public static function fetchDetailQuotaWithProgram($id) {
        return AdmissionQuota::baseEloquent()
        ->with([
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

    public static function fetchQuotaAdmissionProgram($admissionId, $educationProgramId) {
        return AdmissionQuota::where('admission_id', $admissionId)
        ->where('education_program_id', $educationProgramId)
        ->first();
    }
}