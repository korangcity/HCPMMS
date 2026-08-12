<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\HealthReportStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class HealthReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'generated_by',
        'title',
        'period_start',
        'period_end',
        'status',
        'summary',
        'content',
        'generated_at',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => HealthReportStatus::class,
            'period_start' => 'immutable_date',
            'period_end' => 'immutable_date',
            'summary' => 'array',
            'generated_at' => 'immutable_datetime',
            'reviewed_at' => 'immutable_datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function scopeForPatient(
        Builder $query,
        int $patientId
    ): Builder {
        return $query->where('patient_id', $patientId);
    }

    public function scopeGenerated(Builder $query): Builder
    {
        return $query->where(
            'status',
            HealthReportStatus::Generated
        );
    }
}
