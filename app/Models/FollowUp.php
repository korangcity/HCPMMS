<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FollowUpStatus;
use App\Enums\FollowUpType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'patient_id',
        'doctor_id',
        'type',
        'status',
        'due_at',
        'title',
        'instructions',
        'completed_at',
        'completion_notes',
        'notified_at',
    ];

    protected $casts = [
        'type' => FollowUpType::class,
        'status' => FollowUpStatus::class,
        'due_at' => 'immutable_datetime',
        'completed_at' => 'immutable_datetime',
        'notified_at' => 'immutable_datetime',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', FollowUpStatus::Pending);
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query
            ->pending()
            ->where('due_at', '<=', now());
    }

    public function complete(?string $notes = null): void
    {
        $this->update([
            'status' => FollowUpStatus::Completed,
            'completed_at' => now(),
            'completion_notes' => $notes,
        ]);
    }

    public function markOverdue(): void
    {
        if (
            $this->status === FollowUpStatus::Pending &&
            $this->due_at->isPast()
        ) {
            $this->update([
                'status' => FollowUpStatus::Overdue,
            ]);
        }
    }
}
