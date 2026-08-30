<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ChronicDiseaseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ChronicDisease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'diagnosed_at' => 'date',
            'status' => ChronicDiseaseStatus::class,
        ];
    }


    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }
}
