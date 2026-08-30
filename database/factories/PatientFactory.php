<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PatientStatus;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'national_code' => fake()
                ->unique()
                ->numerify('##########'),

            'birth_date' => fake()
                ->dateTimeBetween(
                    '-90 years',
                    '-18 years'
                )
                ->format('Y-m-d'),

            'blood_type' => fake()->randomElement([
                'A+',
                'A-',
                'B+',
                'B-',
                'AB+',
                'AB-',
                'O+',
                'O-',
            ]),

            'emergency_contact_name' => fake()->name(),

            'emergency_contact_phone' => fake()
                ->numerify('09#########'),

            'address' => fake()->address(),

            'notes' => fake()->optional()->sentence(),

            'status' => PatientStatus::ACTIVE,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'status' => PatientStatus::INACTIVE,
        ]);
    }
}
