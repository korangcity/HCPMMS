<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'generated_by',
        'type',
        'status',
        'from',
        'to',
        'data',
        'error_message',
        'generated_at',
    ];

    protected $casts = [
        'type' => ReportType::class,
        'status' => ReportStatus::class,
        'from' => 'immutable_datetime',
        'to' => 'immutable_datetime',
        'generated_at' => 'immutable_datetime',
        'data' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where(
            'status',
            ReportStatus::Completed
        );
    }

    public function scopeForPatient(
        Builder $query,
        int $patientId
    ): Builder {
        return $query->where('patient_id', $patientId);
    }

    public function scopeBetween(
        Builder $query,
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ): Builder {
        return $query
            ->where('from', '>=', $from)
            ->where('to', '<=', $to);
    }

    public function isCompleted(): bool
    {
        return $this->status === ReportStatus::Completed;
    }
}
