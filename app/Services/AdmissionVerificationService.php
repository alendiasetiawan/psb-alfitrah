<?php

namespace App\Services;

use App\Enums\VerificationStatusEnum;

class AdmissionVerificationService
{
   public function isStudentCanEditBiodata($registrationPaymentStatus, $biodataStatus)
   {

      if ($registrationPaymentStatus == VerificationStatusEnum::VALID && ($biodataStatus == VerificationStatusEnum::NOT_STARTED || $biodataStatus == VerificationStatusEnum::INVALID)) {
         return true;
      }

      return false;
   }


   public function isStudentCadEditAttachment($registrationPaymentStatus, $attachmentStatus)
   {
      if ($registrationPaymentStatus == VerificationStatusEnum::VALID && ($attachmentStatus == VerificationStatusEnum::NOT_STARTED || $attachmentStatus == VerificationStatusEnum::INVALID)) {
         return true;
      }

      return false;
   }

   public function isStudentCanCreateQr($registrationPaymentStatus, $biodataStatus, $attachmentStatus)
   {
      if ($registrationPaymentStatus == VerificationStatusEnum::VALID && $biodataStatus == VerificationStatusEnum::VALID && $attachmentStatus == VerificationStatusEnum::VALID) {
         return true;
      }

      return false;
   }
}
