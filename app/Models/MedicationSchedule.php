<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MedicationScheduleFrequency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MedicationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_item_id',
        'frequency',
        'scheduled_time',
        'interval_hours',
        'starts_at',
        'ends_at',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'frequency' => MedicationScheduleFrequency::class,
        'scheduled_time' => 'string',
        'starts_at' => 'immutable_date',
        'ends_at' => 'immutable_date',
        'is_active' => 'boolean',
    ];

    public function prescriptionItem(): BelongsTo
    {
        return $this->belongsTo(PrescriptionItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isActiveFor(\DateTimeInterface $date): bool
    {
        $checkDate = \Illuminate\Support\Carbon::instance(
            \DateTime::createFromInterface($date)
        )->startOfDay();

        $startsAt = $this->starts_at->startOfDay();

        if ($checkDate->lt($startsAt)) {
            return false;
        }

        if ($this->ends_at !== null && $checkDate->gt($this->ends_at->startOfDay())) {
            return false;
        }

        return $this->is_active;
    }
}
