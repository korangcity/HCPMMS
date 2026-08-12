<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PrescriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'status',
        'prescribed_at',
        'valid_from',
        'valid_until',
        'notes',
    ];

    protected $casts = [
        'status' => PrescriptionStatus::class,
        'prescribed_at' => 'immutable_date',
        'valid_from' => 'immutable_date',
        'valid_until' => 'immutable_date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PrescriptionStatus::Active);
    }

    public function isActive(): bool
    {
        return $this->status === PrescriptionStatus::Active;
    }
}
