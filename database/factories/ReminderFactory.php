<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ReminderStatus;
use App\Enums\ReminderType;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reminder>
 */
final class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'medication_id' => null,
            'type' => ReminderType::Medication,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->sentence(),
            'scheduled_at' => fake()->dateTimeBetween(
                '-2 days',
                '+7 days',
            ),
            'completed_at' => null,
            'status' => ReminderStatus::Pending,
            'notified_at' => null,
            'completed_by' => null,
        ];
    }

    public function medication(): static
    {
        return $this->state(function (): array {
            return [
                'type' => ReminderType::Medication,
                'medication_id' => Medication::factory(),
                'title' => 'یادآوری مصرف دارو',
            ];
        });
    }

    public function appointment(): static
    {
        return $this->state([
            'type' => ReminderType::Appointment,
            'medication_id' => null,
            'title' => 'یادآوری ویزیت پزشک',
        ]);
    }

    public function labTest(): static
    {
        return $this->state([
            'type' => ReminderType::LabTest,
            'medication_id' => null,
            'title' => 'یادآوری انجام آزمایش',
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => ReminderStatus::Completed,
            'completed_at' => now(),
            'completed_by' => User::factory(),
        ]);
    }

    public function missed(): static
    {
        return $this->state([
            'status' => ReminderStatus::Missed,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state([
            'status' => ReminderStatus::Cancelled,
        ]);
    }
}
