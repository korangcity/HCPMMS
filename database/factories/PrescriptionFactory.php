<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PrescriptionStatus;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prescription>
 */
final class PrescriptionFactory extends Factory
{
    protected $model = Prescription::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => User::factory(),
            'status' => PrescriptionStatus::Active,
            'prescribed_at' => now()->toDateString(),
            'valid_from' => now()->toDateString(),
            'valid_until' => now()->addDays(30)->toDateString(),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function draft(): static
    {
        return $this->state([
            'status' => PrescriptionStatus::Draft,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state([
            'status' => PrescriptionStatus::Cancelled,
        ]);
    }
}
