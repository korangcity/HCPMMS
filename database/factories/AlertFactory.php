<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AlertPriority;
use App\Enums\AlertStatus;
use App\Enums\AlertType;
use App\Models\Alert;
use App\Models\AlertRule;
use App\Models\Patient;
use App\Models\VitalSign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alert>
 */
final class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'vital_sign_id' => VitalSign::factory(),
            'alert_rule_id' => AlertRule::factory(),

            'type' => AlertType::AbnormalVitalSign,
            'priority' => AlertPriority::Medium,
            'status' => AlertStatus::Open,

            'title' => 'مقدار غیرعادی علائم حیاتی',
            'message' => 'مقدار ثبت‌شده خارج از محدوده مجاز است.',

            'observed_value' => 120,
            'expected_min' => 60,
            'expected_max' => 100,

            'unit' => 'bpm',
            'triggered_at' => now(),

            'acknowledged_at' => null,
            'acknowledged_by' => null,

            'resolved_at' => null,
            'resolved_by' => null,

            'resolution_note' => null,

            'deduplication_key' => fake()->sha256(),
        ];
    }

    public function critical(): static
    {
        return $this->state(fn (): array => [
            'priority' => AlertPriority::Critical,
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (): array => [
            'status' => AlertStatus::Resolved,
            'resolved_at' => now(),
        ]);
    }
}
