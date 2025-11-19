<?php

namespace App\Models\AdmissionData;

use App\Models\User;
use App\Models\Core\Branch;
use App\Models\Core\Regency;
use App\Models\Core\Village;
use App\Models\Core\District;
use App\Models\Core\Province;
use App\Models\Core\Admission;
use App\Models\Core\AdmissionBatch;
use App\Models\Core\EducationProgram;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlacementTest\TestQrCodes;
use App\Models\PlacementTest\PlacementTestResult;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\PlacementTest\PlacementTestPresence;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
   protected $fillable = [
      'user_id',
      'parent_id',
      'branch_id',
      'education_program_id',
      'admission_id',
      'admission_batch_id',
      'province_id',
      'regency_id',
      'district_id',
      'village_id',
      'reg_number',
      'name',
      'gender', //Enum ("Laki-Laki", "Perempuan")
      'birth_place',
      'birth_date',
      'address',
      'country_code',
      'mobile_phone',
      'nisn',
      'old_school_name',
      'old_school_address',
      'npsn',
      'is_scholarship',
      'is_walkout'
   ];

   public function province(): BelongsTo
   {
      return $this->belongsTo(Province::class);
   }

   public function regency(): BelongsTo
   {
      return $this->belongsTo(Regency::class);
   }

   public function district(): BelongsTo
   {
      return $this->belongsTo(District::class);
   }

   public function village(): BelongsTo
   {
      return $this->belongsTo(Village::class);
   }

   public function admissionBatch(): BelongsTo
   {
      return $this->belongsTo(AdmissionBatch::class);
   }

   public function branch(): BelongsTo
   {
      return $this->belongsTo(Branch::class);
   }

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   public function educationProgram(): BelongsTo
   {
      return $this->belongsTo(EducationProgram::class);
   }

   public function admission(): BelongsTo
   {
      return $this->belongsTo(Admission::class);
   }

   public function registrationPayment(): HasOne
   {
      return $this->hasOne(RegistrationPayment::class);
   }

   public function admissionVerification(): HasOne
   {
      return $this->hasOne(AdmissionVerification::class);
   }

   public function placementTestResult(): HasOne
   {
      return $this->hasOne(PlacementTestResult::class);
   }

   public function parent(): BelongsTo
   {
      return $this->belongsTo(ParentModel::class);
   }

   public function multiStudent(): HasOne
   {
      return $this->hasOne(MultiStudent::class);
   }

   public function studentAttachment(): HasOne
   {
      return $this->hasOne(StudentAttachment::class);
   }

   public function testQrCode(): HasOne
   {
      return $this->hasOne(TestQrCodes::class);
   }

   public function placementTestPresence(): HasOne
   {
      return $this->hasOne(PlacementTestPresence::class);
   }

   //Scope for get branch_name and program_names
   public function scopeJoinBranchAndProgram($query)
   {
      return $query
         ->join('branches', 'students.branch_id', 'branches.id')
         ->join('education_programs', 'students.education_program_id', 'education_programs.id')
         ->addSelect(
            'branches.name as branch_name',
            'education_programs.name as program_name'
         );
   }

   //Scope for get admission verification status
   public function scopeJoinAdmissionVerification($query)
   {
      return $query
         ->join('admission_verifications', 'students.id', 'admission_verifications.student_id')
         ->addSelect('admission_verifications.*'); 
   }

   //Scope for get academic year
   public function scopeJoinAdmission($query)
   {
      return $query
         ->join('admissions', 'students.admission_id', 'admissions.id')
         ->addSelect('admissions.name as academic_year');
   }

   public static function baseEloquent($studentId = null, $branchId = null, $educationProgramId = null, $admissionId = null, $admissionBatchId = null, $searchStudent = null)
   {
      return self::when(!empty($studentId), function ($query) use ($studentId) {
            $query->where('students.id', $studentId);
         })
         ->when(!empty($branchId), function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
         })
         ->when(!empty($educationProgramId), function ($query) use ($educationProgramId) {
            $query->where('education_program_id', $educationProgramId);
         })
         ->when(!empty($admissionId), function ($query) use ($admissionId) {
            $query->where('admission_id', $admissionId);
         })
         ->when(!empty($admissionBatchId), function ($query) use ($admissionBatchId) {
            $query->where('admission_batch_id', $admissionBatchId);
         })
         ->when(!empty($searchStudent), function ($query) use ($searchStudent) {
            $query->where('students.name', 'like', '%' . $searchStudent . '%');
         });
   }
}
