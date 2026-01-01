<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\AdmissionData\MultiStudent;
use App\Models\Core\Owner;
use Illuminate\Support\Str;
use App\Models\Core\Reseller;
use App\Models\AdmissionData\Student;
use Illuminate\Notifications\Notifiable;
use App\Models\AdmissionData\ParentModel;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'username',
        'password',
        'fullname',
        'gender',
        'photo',
        'mobile_phone',
        'otp',
        'otp_expired_at',
        'is_verified',
        'verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }


    public function parent(): HasOne
    {
        return $this->hasOne(ParentModel::class);
    }


    public function multiStudent(): HasOne
    {
        return $this->hasOne(MultiStudent::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
