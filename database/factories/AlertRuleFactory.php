<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AlertPriority;
use App\Enums\VitalSignType;
use App\Models\AlertRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlertRule>
 */
final class AlertRuleFactory extends Factory
{
    protected $model = AlertRule::class;

    public function definition(): array
    {
        return [
            'name' => 'Heart rate abnormality',
            'vital_type' => VitalSignType::HeartRate,
            'min_value' => 50,
            'max_value' => 100,
            'priority' => AlertPriority::Medium,
            'is_active' => true,
            'deduplication_minutes' => 30,
            'description' => 'Detect abnormal heart rate.',
        ];
    }

    public function critical(): static
    {
        return $this->state(fn (): array => [
            'priority' => AlertPriority::Critical,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }

    public function forVital(
        VitalSignType $type,
        float $min,
        float $max,
    ): static {
        return $this->state(fn (): array => [
            'vital_type' => $type,
            'min_value' => $min,
            'max_value' => $max,
        ]);
    }
}
