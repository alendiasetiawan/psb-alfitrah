<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionFee extends Model
{
    protected $fillable = [
        'admission_id',
        'education_program_id',
        'registration_fee',
        'internal_registration_fee'
    ];

    /**
     * Get the admission that owns the AdmissionFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the educationProgram that owns the AdmissionFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class);
    }

    public function setRegistrationFeeAttribute($value)
    {
        $this->attributes['registration_fee'] = (int) str_replace('.', '', $value);
    }

    public function setInternalRegistrationFeeAttribute($value)
    {
        $this->attributes['internal_registration_fee'] = (int) str_replace('.', '', $value);
    }
}
