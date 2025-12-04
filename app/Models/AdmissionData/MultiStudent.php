<?php

namespace App\Models\AdmissionData;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultiStudent extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'student_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
