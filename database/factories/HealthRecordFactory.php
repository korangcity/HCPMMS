<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\HealthRecordType;
use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthRecord>
 */
final class HealthRecordFactory extends Factory
{
    protected $model = HealthRecord::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'created_by' => User::factory(),
            'type' => fake()->randomElement(HealthRecordType::cases()),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'recorded_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'metadata' => [
                'source' => 'manual',
            ],
        ];
    }
}
