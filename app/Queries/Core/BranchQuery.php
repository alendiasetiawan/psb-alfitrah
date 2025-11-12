<?php

namespace App\Queries\Core;

use App\Models\Core\Branch;

class BranchQuery {

    public static function getAllBranchTotalProgram() {
        return Branch::withCount('educationPrograms as total_program')
        ->get();
    }

    public static function fetchBranchWithProgram($id) {
        return Branch::with('educationPrograms')
        ->where('id', $id)
        ->first();
    }

    public static function getBranchProgramWithQuota($admissionId) {
        return Branch::with([
            'educationPrograms' => function ($query) use ($admissionId) {
                $query->select('id', 'name', 'branch_id')
                ->with([
                    'admissionQuotas' => function ($query) use ($admissionId) {
                        $query->select('id', 'admission_id', 'education_program_id', 'amount', 'status')
                        ->where('admission_id', $admissionId);
                    }
                ]);
            }
        ])
        ->select('id', 'name as branch_name', 'address', 'mobile_phone', 'map_link', 'photo')
        ->get();
    }

    public static function getBranchProgramWithFee($admissionId) {
        return Branch::with([
            'educationPrograms' => function($query) use ($admissionId) {
                $query->select('id', 'name', 'branch_id')
                ->with([
                    'admissionFees' => function($query) use ($admissionId) {
                        $query->select('id', 'admission_id', 'education_program_id', 'registration_fee', 'internal_registration_fee')
                        ->where('admission_id', $admissionId);
                    }
                ]);
            }
        ])
        ->select('id', 'name as branch_name', 'address', 'mobile_phone', 'map_link', 'photo')
        ->get();
    }
}