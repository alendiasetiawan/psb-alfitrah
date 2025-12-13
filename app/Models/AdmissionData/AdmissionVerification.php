<?php

namespace App\Models\AdmissionData;

use App\Enums\VerificationStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionVerification extends Model
{
    protected $fillable = [
        'student_id',
        'registration_payment', //enum('Valid','Tidak Valid','Proses','Belum')
        'biodata', //enum('Valid','Tidak Valid','Proses','Belum')
        'attachment', //enum('Valid','Tidak Valid','Proses','Belum')
        'placement_test', //enum('Belum','Sudah','Tidak Hadir')
        'payment_error_msg',
        'biodata_error_msg',
        'attachment_error_msg',
        'fu_payment', //enum('Belum','Sudah')
        'fu_biodata', //enum('Belum','Sudah')
        'fu_attachment', //enum('Belum','Sudah')
        'fu_placement_test', //enum('Belum','Sudah')
        'biodata_verified_at',
        'attachment_verified_at',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeBiodataPending($query)
    {
        return $query->where('biodata', VerificationStatusEnum::NOT_STARTED);
    }

    public function scopeBiodataProcess($query)
    {
        return $query->whereIn('biodata', [VerificationStatusEnum::PROCESS, VerificationStatusEnum::INVALID]);
    }

    public function scopeBiodataVerified($query)
    {
        return $query->where('biodata', VerificationStatusEnum::VALID);
    }
}
