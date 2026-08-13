<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ReportType;
use App\Jobs\GeneratePatientReport;
use Illuminate\Console\Command;
use Illuminate\Validation\Rules\Enum;
use InvalidArgumentException;

final class GeneratePatientReportCommand extends Command
{
    protected $signature = 'reports:generate
        {patient : Patient ID}
        {type : Report type}
        {from : Start date}
        {to : End date}
        {--user= : User ID who generated the report}';

    protected $description = 'Generate a health report for a patient.';

    public function handle(): int
    {
        $type = ReportType::tryFrom(
            (string) $this->argument('type')
        );

        if ($type === null) {
            $this->error(
                'Invalid report type. Available values: '
                . implode(
                    ', ',
                    array_map(
                        static fn (ReportType $type): string =>
                        $type->value,
                        ReportType::cases()
                    )
                )
            );

            return self::FAILURE;
        }

        $from = (string) $this->argument('from');
        $to = (string) $this->argument('to');

        if (
            ! strtotime($from)
            || ! strtotime($to)
        ) {
            $this->error('Invalid date.');
            return self::FAILURE;
        }

        if (strtotime($from) > strtotime($to)) {
            $this->error(
                'The from date must be before the to date.'
            );

            return self::FAILURE;
        }

        $patientId = (int) $this->argument('patient');
        $userId = $this->option('user');

        GeneratePatientReport::dispatch(
            patientId: $patientId,
            type: $type,
            from: $from,
            to: $to,
            generatedById: $userId !== null
                ? (int) $userId
                : null,
        );

        $this->info('Report generation job dispatched.');

        return self::SUCCESS;
    }
}
