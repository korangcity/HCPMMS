<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReminderStatus;
use App\Enums\ReminderType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medication_id',
        'type',
        'title',
        'description',
        'scheduled_at',
        'completed_at',
        'status',
        'notified_at',
        'completed_by',
    ];

    protected $casts = [
        'type' => ReminderType::class,
        'status' => ReminderStatus::class,
        'scheduled_at' => 'immutable_datetime',
        'completed_at' => 'immutable_datetime',
        'notified_at' => 'immutable_datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where(
            'status',
            ReminderStatus::Pending->value,
        );
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query
            ->pending()
            ->where('scheduled_at', '<=', now());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->pending()
            ->where('scheduled_at', '>', now());
    }

    public function scopeForPatient(
        Builder $query,
        int $patientId,
    ): Builder {
        return $query->where('patient_id', $patientId);
    }

    public function isPending(): bool
    {
        return $this->status === ReminderStatus::Pending;
    }

    public function isCompleted(): bool
    {
        return $this->status === ReminderStatus::Completed;
    }

    public function isMissed(): bool
    {
        return $this->status === ReminderStatus::Missed;
    }
}
