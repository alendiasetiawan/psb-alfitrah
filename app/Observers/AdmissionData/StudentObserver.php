<?php

namespace App\Observers\AdmissionData;


use App\Models\AdmissionData\Student;
use App\Traits\FlushStudentAdmissionDataTrait;

class StudentObserver
{
   use FlushStudentAdmissionDataTrait;
   public $afterCommit = true;
   /**
    * Handle the Student "created" event.
    */
   public function created(Student $student): void
   {
      $this->flushBiodata($student->id);
      $this->flushAttachment($student->id);
   }

   /**
    * Handle the Student "updated" event.
    */
   public function updated(Student $student): void
   {
      $this->flushBiodata($student->id);
      $this->flushAttachment($student->id);
      
   }

   /**
    * Handle the Student "deleted" event.
    */
   public function deleted(Student $student): void
   {
      $this->flushBiodata($student->id);
      $this->flushAttachment($student->id);

   }
}
