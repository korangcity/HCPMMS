<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\VitalSignType;
use App\Models\Patient;
use App\Models\User;
use App\Models\VitalSign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VitalSign>
 */
final class VitalSignFactory extends Factory
{
    protected $model = VitalSign::class;

    public function definition(): array
    {
        $type = fake()->randomElement(VitalSignType::cases());

        return [
            'patient_id' => Patient::factory(),
            'recorded_by' => User::factory(),
            'type' => $type,
            'value' => fake()->randomFloat(2, 50, 150),
            'secondary_value' => null,
            'unit' => $type->unit(),
            'recorded_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'source' => 'manual',
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function bloodPressure(): static
    {
        return $this->state(fn (): array => [
            'type' => VitalSignType::BloodPressure,
            'value' => fake()->numberBetween(90, 160),
            'secondary_value' => fake()->numberBetween(60, 100),
            'unit' => 'mmHg',
        ]);
    }

    public function heartRate(): static
    {
        return $this->state(fn (): array => [
            'type' => VitalSignType::HeartRate,
            'value' => fake()->numberBetween(55, 110),
            'secondary_value' => null,
            'unit' => 'bpm',
        ]);
    }
}
