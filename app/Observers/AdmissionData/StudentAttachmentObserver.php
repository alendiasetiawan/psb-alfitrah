<?php

namespace App\Observers\AdmissionData;

use App\Models\AdmissionData\StudentAttachment;
use App\Traits\FlushStudentAdmissionDataTrait;

class StudentAttachmentObserver
{
   use FlushStudentAdmissionDataTrait;
   public $afterCommit = true;

   /**
    * Handle the StudentAttachment "created" event.
    */
   public function created(StudentAttachment $studentAttachment): void
   {
      $this->flushAttachment($studentAttachment->student_id);
   }

   /**
    * Handle the StudentAttachment "updated" event.
    */
   public function updated(StudentAttachment $studentAttachment): void
   {
      $this->flushAttachment($studentAttachment->student_id);
      
   }

   /**
    * Handle the StudentAttachment "deleted" event.
    */
   public function deleted(StudentAttachment $studentAttachment): void
   {
      $this->flushAttachment($studentAttachment->student_id);
      
   }
}
