<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Models\VitalSign;
use App\Models\HealthRecord;
use App\Models\DailyNote;
use Illuminate\Database\Seeder;

final class HealthMonitoringSeeder extends Seeder
{
    public function run(): void
    {
        Patient::query()
            ->with('user')
            ->limit(10)
            ->get()
            ->each(function (Patient $patient): void {
                $userId = $patient->user?->id;

                VitalSign::factory()
                    ->count(20)
                    ->for($patient)
                    ->state([
                        'recorded_by' => $userId,
                    ])
                    ->create();

                HealthRecord::factory()
                    ->count(5)
                    ->for($patient)
                    ->state([
                        'recorded_by' => $userId,
                    ])
                    ->create();

                DailyNote::factory()
                    ->count(7)
                    ->for($patient)
                    ->state([
                        'created_by' => $userId,
                    ])
                    ->create();
            });
    }
}
