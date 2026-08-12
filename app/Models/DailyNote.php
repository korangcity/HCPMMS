<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DailyNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'created_by',
        'note_date',
        'content',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'note_date' => 'immutable_date',
            'metadata' => 'array',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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
        return $query->whereBetween('note_date', [$from, $to]);
    }
}
