<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
final class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'medical_license_number' => 'MD-' . fake()->unique()->numerify('######'),
            'specialty' => fake()->randomElement([
                'Cardiology',
                'Endocrinology',
                'Pulmonology',
                'Internal Medicine',
            ]),
            'bio' => fake()->optional()->paragraph(),
            'is_available' => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state([
            'is_available' => false,
        ]);
    }
}
