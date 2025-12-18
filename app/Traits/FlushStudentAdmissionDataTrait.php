<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheKeys\Student\StudentAdmissionDataKey;

trait FlushStudentAdmissionDataTrait
{

   public function flushAttachment($studentId)
   {
      $key = StudentAdmissionDataKey::studentAttachment($studentId);
      Cache::forget($key);
   }

   public function flushBiodata($studentId)
   {
      $key = StudentAdmissionDataKey::studentBiodata($studentId);
      Cache::forget($key);
   }
}
