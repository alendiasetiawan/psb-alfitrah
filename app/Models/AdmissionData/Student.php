<?php

namespace App\Models\AdmissionData;

use App\Models\Core\Admission;
use App\Models\Core\AdmissionBatch;
use App\Models\Core\Branch;
use App\Models\Core\District;
use App\Models\Core\EducationProgram;
use App\Models\Core\Province;
use App\Models\Core\Regency;
use App\Models\Core\Village;
use App\Models\Core\WalkoutStudent;
use App\Models\Payment\RegistrationInvoice;
use App\Models\PlacementTest\PlacementTestPresence;
use App\Models\PlacementTest\PlacementTestResult;
use App\Models\PlacementTest\TestQrCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'is_walkout',
        'modified_at'
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
        return $this->hasOne(TestQrCode::class);
    }

    public function placementTestPresence(): HasOne
    {
        return $this->hasOne(PlacementTestPresence::class);
    }

    public function registrationInvoices(): HasMany
    {
        return $this->hasMany(RegistrationInvoice::class);
    }

    public function walkoutStudents(): HasMany
    {
        return $this->hasMany(WalkoutStudent::class);
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

    //Scope for get academic year
    public function scopeJoinAdmission($query)
    {
        return $query
            ->join('admissions', 'students.admission_id', 'admissions.id')
            ->addSelect('admissions.name as academic_year');
    }

    //Scope for get batch name
    public function scopeJoinAdmissionBatch($query)
    {
        return $query
            ->join('admission_batches', 'students.admission_batch_id', 'admission_batches.id')
            ->addSelect('admission_batches.name as batch_name');
    }

    //Scope for get registration payment status
    public function scopeJoinRegistrationPayment($query)
    {
        return $query->join('registration_payments', 'students.id', 'registration_payments.student_id')
            ->addSelect('amount as registration_fee', 'evidence', 'payment_status');
    }

    //Scope for get user detail data
    public function scopeJoinUser($query)
    {
        return $query->join('users', 'students.user_id', 'users.id')
            ->addSelect('users.username', 'users.photo as user_photo');
    }

    //Scope for get placement test result data
    public function scopeJoinPlacementTestResult($query)
    {
        return $query->join('placement_test_results', 'students.id', 'placement_test_results.student_id')
            ->addSelect('placement_test_results.id as test_id', 'psikotest_score', 'read_quran_score', 'parent_interview', 'student_interview', 'final_score', 'final_result', 'final_note', 'publication_status');
    }

    //Scope for get demografi data
    public function scopeJoinDemografi($query)
    {
        return $query->join('provinces', 'students.province_id', 'provinces.id')
            ->join('regencies', 'students.regency_id', 'regencies.id')
            ->join('districts', 'students.district_id', 'districts.id')
            ->join('villages', 'students.village_id', 'villages.id')
            ->addSelect('provinces.name as province_name', 'regencies.name as regency_name', 'districts.name as district_name', 'villages.name as village_name');
    }

    //Scope for get attachment data
    public function scopeJoinStudentAttachment($query)
    {
        return $query->join('student_attachments', 'students.id', 'student_attachments.student_id')
            ->addSelect('student_attachments.photo', 'student_attachments.born_card', 'student_attachments.family_card', 'student_attachments.parent_card', 'student_attachments.modified_at as attachment_modified_at');
    }

    //Scope for get admission verification status
    public function scopeJoinAdmissionVerification($query)
    {
        return $query
            ->join('admission_verifications', 'students.id', 'admission_verifications.student_id')
            ->addSelect('admission_verifications.registration_payment', 'admission_verifications.biodata', 'admission_verifications.attachment', 'admission_verifications.placement_test', 'admission_verifications.fu_payment', 'admission_verifications.fu_biodata', 'admission_verifications.fu_attachment', 'admission_verifications.fu_placement_test', 'admission_verifications.payment_error_msg', 'admission_verifications.biodata_error_msg', 'admission_verifications.attachment_error_msg');
    }

    public static function baseEloquent($studentId = null, $branchId = null, $educationProgramId = null, $admissionId = null, $admissionBatchId = null, $searchStudent = null)
    {
        return self::when(!empty($studentId), function ($query) use ($studentId) {
            $query->where('students.id', $studentId);
        })
            ->when(!empty($branchId), function ($query) use ($branchId) {
                $query->where('students.branch_id', $branchId);
            })
            ->when(!empty($educationProgramId), function ($query) use ($educationProgramId) {
                $query->where('students.education_program_id', $educationProgramId);
            })
            ->when(!empty($admissionId), function ($query) use ($admissionId) {
                $query->where('students.admission_id', $admissionId);
            })
            ->when(!empty($admissionBatchId), function ($query) use ($admissionBatchId) {
                $query->where('students.admission_batch_id', $admissionBatchId);
            })
            ->when(!empty($searchStudent), function ($query) use ($searchStudent) {
                $query->where(function ($query) use ($searchStudent) {
                    $query->where('students.name', 'like', '%' . $searchStudent . '%')
                        ->orWhere('students.mobile_phone', 'like', '%' . $searchStudent . '%');
                });
            });
    }
}
