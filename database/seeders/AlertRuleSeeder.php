<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AlertPriority;
use App\Enums\VitalSignType;
use App\Models\AlertRule;
use Illuminate\Database\Seeder;

final class AlertRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'name' => 'Heart Rate',
                'vital_type' => VitalSignType::HeartRate,
                'min_value' => 50,
                'max_value' => 100,
                'priority' => AlertPriority::Medium,
            ],
            [
                'name' => 'Oxygen Saturation',
                'vital_type' => VitalSignType::OxygenSaturation,
                'min_value' => 92,
                'max_value' => null,
                'priority' => AlertPriority::High,
            ],
            [
                'name' => 'Temperature',
                'vital_type' => VitalSignType::Temperature,
                'min_value' => 36,
                'max_value' => 38,
                'priority' => AlertPriority::Medium,
            ],
            [
                'name' => 'Respiratory Rate',
                'vital_type' => VitalSignType::RespiratoryRate,
                'min_value' => 12,
                'max_value' => 20,
                'priority' => AlertPriority::Medium,
            ],
            [
                'name' => 'Blood Glucose',
                'vital_type' => VitalSignType::BloodGlucose,
                'min_value' => 70,
                'max_value' => 140,
                'priority' => AlertPriority::High,
            ],
        ];

        foreach ($rules as $rule) {
            AlertRule::query()->updateOrCreate(
                [
                    'vital_type' => $rule['vital_type'],
                ],
                [
                    'name' => $rule['name'],
                    'min_value' => $rule['min_value'],
                    'max_value' => $rule['max_value'],
                    'priority' => $rule['priority'],
                    'is_active' => true,
                    'deduplication_minutes' => 30,
                ],
            );
        }
    }
}
