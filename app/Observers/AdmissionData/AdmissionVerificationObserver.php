<?php

namespace App\Observers\AdmissionData;

use App\Traits\FlushStudentAdmissionDataTrait;
use App\Models\AdmissionData\AdmissionVerification;

class AdmissionVerificationObserver
{
   use FlushStudentAdmissionDataTrait;

   public $afterCommit = true;
   /**
    * Handle the AdmissionVerification "created" event.
    */
   public function created(AdmissionVerification $admissionVerification): void
   {
      $this->flushAttachment($admissionVerification->student_id);
      $this->flushBiodata($admissionVerification->student_id);
   }

   /**
    * Handle the AdmissionVerification "updated" event.
    */
   public function updated(AdmissionVerification $admissionVerification): void
   {
      $this->flushAttachment($admissionVerification->student_id);
      $this->flushBiodata($admissionVerification->student_id);
   }

   /**
    * Handle the AdmissionVerification "deleted" event.
    */
   public function deleted(AdmissionVerification $admissionVerification): void
   {
      $this->flushAttachment($admissionVerification->student_id);
      $this->flushBiodata($admissionVerification->student_id);
   }
}
