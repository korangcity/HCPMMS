<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\HealthRecordType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'created_by',
        'type',
        'title',
        'description',
        'recorded_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'type' => HealthRecordType::class,
            'recorded_at' => 'immutable_datetime',
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
}
