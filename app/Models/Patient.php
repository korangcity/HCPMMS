<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PatientStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'address',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'status' => PatientStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(
            Doctor::class,
            'doctor_patient'
        )->withPivot([
            'assigned_at',
            'notes',
        ])->withTimestamps();
    }

    public function caregivers(): BelongsToMany
    {
        return $this->belongsToMany(
            Caregiver::class,
            'caregiver_patient'
        )
            ->withPivot([
                'is_primary',
                'notes',
            ])
            ->withTimestamps();
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

//
//    /*
//     * These relationships will become active
//     * when the corresponding modules are implemented.
//     */
//
    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }
//
//    public function chronicDiseases(): HasMany
//    {
//        return $this->hasMany(ChronicDisease::class);
//    }
//
//    public function medications(): HasMany
//    {
//        return $this->hasMany(Medication::class);
//    }
//
//    public function reminders(): HasMany
//    {
//        return $this->hasMany(Reminder::class);
//    }
//
//    public function alerts(): HasMany
//    {
//        return $this->hasMany(Alert::class);
//    }
//
//    public function appointments(): HasMany
//    {
//        return $this->hasMany(Appointment::class);
//    }
//
//    public function visits(): HasMany
//    {
//        return $this->hasMany(Visit::class);
//    }

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            PatientStatus::ACTIVE
        );
    }

    public function scopeInactive($query)
    {
        return $query->where(
            'status',
            PatientStatus::INACTIVE
        );
    }
}
