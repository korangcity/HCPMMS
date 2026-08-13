<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\Patient;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
final class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $from = now()->subDays(30);
        $to = now();

        return [
            'patient_id' => Patient::factory(),
            'generated_by' => User::factory(),
            'type' => fake()->randomElement(ReportType::cases()),
            'status' => ReportStatus::Completed,
            'from' => $from,
            'to' => $to,
            'data' => [
                'count' => fake()->numberBetween(5, 30),
                'summary' => [
                    'min' => fake()->randomFloat(2, 50, 100),
                    'max' => fake()->randomFloat(2, 100, 180),
                    'average' => fake()->randomFloat(2, 60, 140),
                ],
            ],
            'error_message' => null,
            'generated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (): array => [
            'status' => ReportStatus::Pending,
            'data' => null,
            'generated_at' => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (): array => [
            'status' => ReportStatus::Failed,
            'error_message' => 'Report generation failed.',
        ]);
    }

    public function bloodPressure(): static
    {
        return $this->state(fn (): array => [
            'type' => ReportType::BloodPressure,
        ]);
    }

    public function bloodGlucose(): static
    {
        return $this->state(fn (): array => [
            'type' => ReportType::BloodGlucose,
        ]);
    }

    public function weight(): static
    {
        return $this->state(fn (): array => [
            'type' => ReportType::Weight,
        ]);
    }

    public function vitalSigns(): static
    {
        return $this->state(fn (): array => [
            'type' => ReportType::VitalSigns,
        ]);
    }
}
