<?php

namespace App\Helpers\CacheKeys\Student;

class StudentAdmissionDataKey
{
   //Related table: admission_verifications, student_attachments, students
   public static function studentAttachment(int $studentId): string
   {
      return "student_attachment_{$studentId}";
   }

   //Related table: admission_verifications, students
   public static function studentBiodata(int $studentId): string
   {
      return "student_biodata_{$studentId}";
   }
}
