<?php

namespace App\Queries\Core;

use App\Models\Core\Admission;

class AdmissionQuery {

    public static function paginateAdmissionWithBatches($limitData) {
        return Admission::with('admissionBatches')
        ->withCount('admissionBatches as total_batch')
        ->orderBy('id', 'desc')
        ->paginate($limitData);
    }

    public static function fetchAdmissionDetail($admissionId) {
        return Admission::where('id', $admissionId)
        ->first();
    }
}