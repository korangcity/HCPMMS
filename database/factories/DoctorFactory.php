<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AcademicDegree;
use App\Enums\DoctorSpecialty;
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
            'medical_license_number' => fake()->unique()->numerify('MED-#####'),
            'specialty' => fake()->randomElement(
                DoctorSpecialty::cases()
            ),
            'academic_degree' => fake()->randomElement(
                AcademicDegree::cases()
            ),
            'years_of_experience' => fake()->numberBetween(1, 30),
            'bio' => fake()->optional()->paragraph(),
        ];
    }
}
