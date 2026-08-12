<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\HealthReportStatus;
use App\Models\HealthReport;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthReport>
 */
final class HealthReportFactory extends Factory
{
    protected $model = HealthReport::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'generated_by' => User::factory(),
            'title' => fake()->sentence(5),
            'period_start' => now()->subDays(7)->toDateString(),
            'period_end' => now()->toDateString(),
            'status' => HealthReportStatus::Generated,
            'summary' => [
                'vital_signs' => [
                    'count' => 0,
                    'by_type' => [],
                ],
                'health_records' => [
                    'count' => 0,
                    'by_type' => [],
                ],
                'daily_notes' => [
                    'count' => 0,
                ],
            ],
            'content' => 'گزارش سلامت',
            'generated_at' => now(),
            'reviewed_at' => null,
        ];
    }
}
