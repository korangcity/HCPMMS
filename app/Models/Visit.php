<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'visited_at',
        'chief_complaint',
        'diagnosis',
        'clinical_summary',
        'treatment_plan',
        'patient_instructions',
        'private_notes',
    ];

    protected $casts = [
        'visited_at' => 'immutable_datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function doctorNotes(): HasMany
    {
        return $this->hasMany(DoctorNote::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    public function latestFollowUp(): HasOne
    {
        return $this->hasOne(FollowUp::class)->latestOfMany();
    }
}
