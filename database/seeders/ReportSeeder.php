<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::query()
            ->limit(5)
            ->get();

        $user = User::query()->first();

        if ($patients->isEmpty()) {
            return;
        }

        foreach ($patients as $patient) {
            Report::factory()
                ->count(3)
                ->for($patient)
                ->when(
                    $user !== null,
                    fn ($factory) => $factory->for(
                        $user,
                        'generatedBy'
                    )
                )
                ->create();
        }
    }
}
