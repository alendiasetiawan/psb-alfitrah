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
      'final_note',
      'publication_status',
      'publication_date'
   ];

   public function student(): BelongsTo
   {
      return $this->belongsTo(Student::class);
   }

   //Scope to get only student who has attend the test
   public function scopeJoinPlacementTestPresence($query)
   {
      return $query->join('placement_test_presence', 'placement_test_presence.student_id', 'placement_test_result.student_id')
      ->addSelect('placement_test_presences.check_in_time');
   }

   //Scope to get student detail data
   public function scopeJoinStudent($query)
   {
      return $query->join('students', 'students.id', 'placement_test_results.student_id')
      ->addSelect('students.name as student_name', 'gender' , 'admission_id', 'admission_batch_id', 'branch_id');
   }

   public static function baseEloquent($studentId = null, $publicationStatus = null)
   {
      return self::when($studentId, function ($query) use ($studentId) {
         return $query->where('student_id', $studentId);
      })
      ->when($publicationStatus, function ($query) use ($publicationStatus) {
         return $query->where('publication_status', $publicationStatus);
      });
   }
}
