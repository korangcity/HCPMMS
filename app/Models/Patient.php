<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PatientGender;
use App\Enums\PatientStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Patient extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'national_code',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'phone',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'immutable_date',
            'gender' => PatientGender::class,
            'status' => PatientStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chronicDiseases(): BelongsToMany
    {
        return $this->belongsToMany(
            ChronicDisease::class,
            'patient_chronic_disease'
        )->withPivot([
            'diagnosed_at',
            'notes',
            'is_active',
        ])->withTimestamps();
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'patient_doctor',
            'patient_id',
            'doctor_id'
        )->withPivot([
            'status',
            'started_at',
            'ended_at',
            'notes',
        ])->withTimestamps();
    }

    public function caregivers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'patient_caregiver',
            'patient_id',
            'caregiver_id'
        )->withPivot([
            'status',
            'started_at',
            'ended_at',
            'notes',
        ])->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PatientStatus::Active);
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function vitalSigns(): HasMany
    {
        return $this->hasMany(VitalSign::class);
    }

    public function dailyNotes(): HasMany
    {
        return $this->hasMany(DailyNote::class);
    }

    public function healthReports(): HasMany
    {
        return $this->hasMany(HealthReport::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }


    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }


}
