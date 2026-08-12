<?php

namespace App\Models;

use App\Enums\CaregiverType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caregiver extends Model
{
    /** @use HasFactory<\Database\Factories\CaregiverFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'national_identifier',
        'address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => CaregiverType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(
            Patient::class,
            'caregiver_patient'
        )->withPivot([
            'assigned_at',
            'ended_at',
            'is_primary',
        ])->withTimestamps();
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with([
            'user',
            'patients.user',
        ]);
    }
}
