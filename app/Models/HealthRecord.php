<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'chronic_disease_id',
        'recorded_by',
        'appointment_id',
        'visit_id',
        'measurement_information',
        'medication_information',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'measurement_information'=>'array',
            'medication_information'=>'array',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function chronicDisease(): BelongsTo
    {
        return $this->belongsTo(ChronicDisease::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
}
