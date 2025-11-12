<?php

namespace App\Services;

use App\Enums\VerificationStatusEnum;

class AdmissionVerificationService 
{
    public function isStudentCanEditBiodata($registrationPaymentStatus, $biodataStatus) {

        if ($registrationPaymentStatus == VerificationStatusEnum::VALID && ($biodataStatus == VerificationStatusEnum::NOT_STARTED || $biodataStatus == VerificationStatusEnum::INVALID)) {
            return true;
        }

        return false;
    }
}