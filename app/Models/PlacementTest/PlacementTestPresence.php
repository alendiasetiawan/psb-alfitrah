<?php

namespace App\Models\PlacementTest;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementTestPresence extends Model
{
   protected $fillable = [
      'test_qr_code_id',
      'check_in_time'
   ];

   public function student(): BelongsTo
   {
      return $this->belongsTo(Student::class);
   }
}
