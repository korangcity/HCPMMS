<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Visit>
 */
final class VisitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'visited_at' => now(),
            'chief_complaint' => fake()->sentence(),
            'diagnosis' => fake()->sentence(),
            'clinical_summary' => fake()->paragraph(),
            'treatment_plan' => fake()->paragraph(),
            'patient_instructions' => fake()->paragraph(),
            'private_notes' => fake()->optional()->paragraph(),
        ];
    }
}
