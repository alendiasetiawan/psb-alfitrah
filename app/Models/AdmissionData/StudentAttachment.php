<?php

namespace App\Models\AdmissionData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttachment extends Model
{
   protected $fillable = [
      'student_id',
      'photo',
      'parent_card',
      'born_card',
      'family_card',
      'photo_status',
      'parent_card_status',
      'born_card_status',
      'family_card_status',
      'modified_at'
   ];

   public function student(): BelongsTo
   {
      return $this->belongsTo(Student::class);
   }
}
