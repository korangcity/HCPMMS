<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MedicationForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Medication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'generic_name',
        'brand_name',
        'form',
        'strength',
        'manufacturer',
        'description',
        'is_active',
    ];

    protected $casts = [
        'form' => MedicationForm::class,
        'is_active' => 'boolean',
    ];

    public function prescriptionItems(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
