<?php 

namespace App\Queries\Core;

use App\Models\Core\AdmissionBatch;

class AdmissionBatchQuery {

    public static function fetchAdmissionBatchDetail($id) {
        return AdmissionBatch::with([
            'admission' => function ($query) {
                $query->select('id', 'name')
                ->withTrashed();
            }
        ])
        ->where('id', $id)
        ->firstOrFail();
    }
}