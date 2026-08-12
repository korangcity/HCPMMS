<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Patient;
use App\Services\HealthMonitoring\HealthReportService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

final class GenerateHealthReports extends Command
{
    protected $signature = 'health:generate-reports
                            {--days=7 : Number of days to include}
                            {--patient= : Generate report for a specific patient}';

    protected $description = 'Generate health reports for patients';

    public function handle(
        HealthReportService $reportService,
    ): int {
        $days = max(1, (int) $this->option('days'));

        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        $query = Patient::query()
            ->with('user');

        if ($patientId = $this->option('patient')) {
            $query->whereKey((int) $patientId);
        }

        $count = 0;

        $query->chunkById(100, function ($patients) use (
            $reportService,
            $from,
            $to,
            &$count,
        ): void {
            foreach ($patients as $patient) {
                $reportService->generate(
                    patient: $patient,
                    from: Carbon::instance($from),
                    to: Carbon::instance($to),
                    generatedBy: $patient->user?->id,
                );

                $count++;
            }
        });

        $this->info(
            sprintf('%d health reports generated.', $count),
        );

        return self::SUCCESS;
    }
}
