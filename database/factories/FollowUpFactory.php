<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FollowUpStatus;
use App\Enums\FollowUpType;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\FollowUp>
 */
final class FollowUpFactory extends Factory
{
    public function definition(): array
    {
        return [
            'visit_id' => Visit::factory(),
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'type' => FollowUpType::Appointment,
            'status' => FollowUpStatus::Pending,
            'due_at' => now()->addDays(7),
            'title' => fake()->sentence(),
            'instructions' => fake()->paragraph(),
        ];
    }

    public function completed(): static
    {
        return $this->state([
            'status' => FollowUpStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state([
            'status' => FollowUpStatus::Pending,
            'due_at' => now()->subDay(),
        ]);
    }
}
