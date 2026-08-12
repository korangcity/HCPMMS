<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'medical_record_number',
        'date_of_birth',
        'gender',
        'emergency_contact_name',
        'emergency_contact_phone',
        'address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'immutable_date',
            'gender' => Gender::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(
            Doctor::class,
            'doctor_patient'
        )->withPivot([
            'assigned_at',
            'ended_at',
            'is_primary',
        ])->withTimestamps();
    }

    public function caregivers(): BelongsToMany
    {
        return $this->belongsToMany(
            Caregiver::class,
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
            'doctors.user',
            'caregivers.user',
        ]);
    }

    public function scopeActiveAssignments(Builder $query): Builder
    {
        return $query->whereHas('doctors', function (Builder $doctorQuery): void {
            $doctorQuery->whereNull('doctor_patient.ended_at');
        });
    }

}
