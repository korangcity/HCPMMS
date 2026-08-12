<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'medical_license_number',
        'specialty',
        'bio',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
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
            'doctor_patient'
        )->withPivot([
            'assigned_at',
            'ended_at',
            'is_primary',
        ])->withTimestamps();
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with([
            'user',
            'patients.user',
        ]);
    }
}
