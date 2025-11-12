<?php

namespace App\Queries\Core;

use App\Models\Core\Branch;
use Illuminate\Support\Facades\DB;
use App\Models\Core\EducationProgram;

class EducationProgramQuery {

    public static function fetchDetailProgram($id) {
        return EducationProgram::with(['branch' => function ($query) {
            $query->select('id', 'name')
            ->withTrashed();
        }])
        ->where('id', $id)
        ->first();
    }

    public static function getProgramsBranchInAdmission($admissionId = null) {
        return Branch::with(['educationPrograms' => function ($query) use ($admissionId) {
            $query->withCount(['students as total_student' => function ($query) use ($admissionId) {
                $query->where('admission_id', $admissionId);
            }]);
        }])
        ->orderBy('id', 'desc')
        ->get();
    }

    public static function getProgramInBranch($branchId) {
        return EducationProgram::where('branch_id', $branchId)
        ->whereHas('admissionQuotas')
        ->get();
    }
}