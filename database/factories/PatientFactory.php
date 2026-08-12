<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
final class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'medical_record_number' => 'MRN-' . fake()->unique()->numerify('######'),
            'date_of_birth' => fake()->dateTimeBetween(
                '-90 years',
                '-18 years'
            )->format('Y-m-d'),
            'gender' => fake()->randomElement(Gender::cases()),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->numerify('09#########'),
            'address' => fake()->address(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
