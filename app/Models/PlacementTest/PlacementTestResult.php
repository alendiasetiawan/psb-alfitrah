<?php

namespace App\Models\PlacementTest;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementTestResult extends Model
{
   protected $fillable = [
      'student_id',
      'psikotest_score',
      'psikotest_note',
      'read_quran_score',
      'read_quran_tester',
      'read_quran_note',
      'parent_interview',
      'parent_interview_tester',
      'parent_interview_note',
      'student_interview',
      'student_interview_tester',
      'student_interview_note',
      'final_score',
      'final_result',
      'publication_status',
      'publication_date'
   ];

   public function student(): BelongsTo
   {
      return $this->belongsTo(Student::class);
   }
}
