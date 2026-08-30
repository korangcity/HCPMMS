<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CaregiverStatus;
use Database\Factories\CaregiverFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Caregiver extends Model
{
    /** @use HasFactory<CaregiverFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'relationship',
        'address',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => CaregiverStatus::class,
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
        )
            ->withPivot([
                'is_primary',
                'notes',
            ])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            CaregiverStatus::ACTIVE
        );
    }

    public function scopeInactive($query)
    {
        return $query->where(
            'status',
            CaregiverStatus::INACTIVE
        );
    }
}
