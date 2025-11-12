<?php

namespace App\Models\Core;

use App\Models\AdmissionData\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionBatch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admission_id',
        'name',
        'open_date',
        'close_date',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
    
    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public static function baseEloquent($admissionId) {
        return self::when(!empty($admissionId), function ($query) use ($admissionId) {
            $query->where('admission_id', $admissionId);
        });
    }
}
