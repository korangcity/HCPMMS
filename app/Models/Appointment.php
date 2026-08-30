<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'scheduled_at',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'status' => AppointmentStatus::class,
        ];
    }

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
    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }

}
