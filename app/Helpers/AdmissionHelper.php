<?php

namespace App\Helpers;

use App\Models\Core\Admission;
use App\Models\Core\AdmissionBatch;

class AdmissionHelper {

    public static function activeAdmission() {
        //Find if there is admission with status "Buka"
        $checkActive = Admission::where('status', 'Buka')->exists();

        if ($checkActive) {
            $activeAdmission = Admission::where('status', 'Buka')->first();
            return $activeAdmission;
        } else {
            $findLastAdmission = Admission::latest()->first();
            return $findLastAdmission;
        }
    }

    public static function activeAdmissionBatch($admissionId) {
        $admissionBatch = AdmissionBatch::where('admission_id', $admissionId)
        ->where(function($query) {
            $query->where('open_date', '<=', date('Y-m-d'))
            ->where('close_date', '>', date('Y-m-d'));
        })
        ->first();

        $activeAdmissionBatch =  $admissionBatch ? $admissionBatch : AdmissionBatch::where('admission_id', $admissionId)->latest()->first();
        
        return $activeAdmissionBatch;
    }

    public static function isAdmissionOpen() {
        $checkOpen = Admission::join('admission_batches', 'admissions.id', 'admission_batches.admission_id')
        ->where('admissions.status', 'Buka')
        ->where(function($query) {
            $query->where('admission_batches.open_date', '<=', date('Y-m-d'))
            ->where('admission_batches.close_date', '>', date('Y-m-d'));
        })
        ->exists();

        return $checkOpen;
    }
}