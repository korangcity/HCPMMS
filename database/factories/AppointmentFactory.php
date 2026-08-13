<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Appointment>
 */
final class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'scheduled_at' => now()->addDays(
                fake()->numberBetween(1, 30)
            ),
            'duration_minutes' => 30,
            'status' => AppointmentStatus::Scheduled,
            'reason' => fake()->sentence(),
            'patient_note' => fake()->optional()->paragraph(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state([
            'status' => AppointmentStatus::Confirmed,
            'confirmed_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => AppointmentStatus::Completed,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state([
            'status' => AppointmentStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_reason' => 'لغو توسط کاربر',
        ]);
    }
}
