<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AcademicDegree;
use App\Enums\DoctorSpecialty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'medical_license_number',
        'specialty',
        'academic_degree',
        'years_of_experience',
        'bio',
    ];

    protected function casts(): array
    {
        return [
            'specialty' => DoctorSpecialty::class,
            'academic_degree' => AcademicDegree::class,
            'years_of_experience' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(
            Patient::class,
            'doctor_patient'
        )->withPivot([
            'assigned_at',
            'notes',
        ])->withTimestamps();
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

}
