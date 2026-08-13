<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'scheduled_at',
        'duration_minutes',
        'status',
        'reason',
        'patient_note',
        'cancellation_reason',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'immutable_datetime',
        'confirmed_at' => 'immutable_datetime',
        'cancelled_at' => 'immutable_datetime',
        'status' => AppointmentStatus::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function visit(): HasOne
    {
        return $this->hasOne(Visit::class);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->where('scheduled_at', '>=', now())
            ->whereIn('status', [
                AppointmentStatus::Scheduled,
                AppointmentStatus::Confirmed,
            ]);
    }

    public function scopeForDoctor(
        Builder $query,
        int $doctorId
    ): Builder {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForPatient(
        Builder $query,
        int $patientId
    ): Builder {
        return $query->where('patient_id', $patientId);
    }

    public function isCancellable(): bool
    {
        return in_array(
            $this->status,
            [
                AppointmentStatus::Scheduled,
                AppointmentStatus::Confirmed,
            ],
            true
        );
    }
}
