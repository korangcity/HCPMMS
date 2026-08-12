<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DoseUnit;
use App\Enums\MedicationRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medication_id',
        'dose',
        'dose_unit',
        'route',
        'quantity',
        'duration_days',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'dose' => 'decimal:2',
        'dose_unit' => DoseUnit::class,
        'route' => MedicationRoute::class,
    ];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(MedicationSchedule::class);
    }
}
