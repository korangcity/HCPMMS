<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PatientGender;
use App\Enums\PatientStatus;
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
            'national_code' => fake()->unique()->numerify('##########'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-18 years'),
            'gender' => fake()->randomElement(PatientGender::cases()),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relation' => 'فرزند',
            'status' => PatientStatus::Active,
        ];
    }

    public function inactive(): static
    {
        return $this->state([
            'status' => PatientStatus::Inactive,
        ]);
    }
}
