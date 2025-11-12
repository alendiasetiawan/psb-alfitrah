<?php

namespace App\Models\Core;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionQuota extends Model
{
    protected $fillable = [
        'admission_id',
        'education_program_id',
        'amount',
        'status' // enum('Buka','Tutup')
    ];

    /**
     * Get the admission that owns the AdmissionQuota
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the educationProgram that owns the AdmissionQuota
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class);
    }

    public static function baseEloquent($admissionId = null, $educationProgramId = null, $status = null) {
        return self::when(!empty($admissionId), function ($query) use ($admissionId) {
            $query->where('admission_id', $admissionId);
        })
        ->when(!empty($educationProgramId), function ($query) use ($educationProgramId) {
            $query->where('education_program_id', $educationProgramId);
        })
        ->when(!empty($status), function ($query) use ($status) {
            $query->where('status', $status);
        });
    }

    public static function baseQuery() {
        return DB::table('admission_quotas');
    }
}
