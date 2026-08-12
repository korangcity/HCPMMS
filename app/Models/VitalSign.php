<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VitalSignType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class VitalSign extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'patient_id',
        'recorded_by',
        'type',
        'value',
        'secondary_value',
        'unit',
        'recorded_at',
        'source',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => VitalSignType::class,
            'value' => 'decimal:2',
            'secondary_value' => 'decimal:2',
            'recorded_at' => 'immutable_datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeForPatient(
        Builder $query,
        int $patientId
    ): Builder {
        return $query->where('patient_id', $patientId);
    }

    public function scopeBetween(
        Builder $query,
        mixed $from,
        mixed $to
    ): Builder {
        return $query->whereBetween('recorded_at', [$from, $to]);
    }
}
