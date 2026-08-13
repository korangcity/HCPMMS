<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Events\ReportGenerated;
use App\Models\Patient;
use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportGeneratedNotification;
use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Throwable;

final class GeneratePatientReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private readonly int $patientId,
        private readonly ReportType $type,
        private readonly string $from,
        private readonly string $to,
        private readonly ?int $generatedById = null,
    ) {}

    public function handle(ReportService $reportService): void
    {
        $patient = Patient::query()->findOrFail($this->patientId);

        $generatedBy = $this->generatedById !== null
            ? User::query()->find($this->generatedById)
            : null;

        $report = $reportService->generate(
            patient: $patient,
            type: $this->type,
            from: Carbon::parse($this->from),
            to: Carbon::parse($this->to),
            generatedBy: $generatedBy,
        );

        ReportGenerated::dispatch($report);

        $this->notifyRelevantUsers($patient, $report);
    }

    private function notifyRelevantUsers(
        Patient $patient,
        Report $report
    ): void {
        $patient->loadMissing([
            'user',
            'doctor',
            'caregivers',
        ]);

        if ($patient->user !== null) {
            $patient->user->notify(
                new ReportGeneratedNotification($report)
            );
        }

        if ($patient->doctor !== null) {
            $patient->doctor->notify(
                new ReportGeneratedNotification($report)
            );
        }

        foreach ($patient->caregivers as $caregiver) {
            $caregiver->notify(
                new ReportGeneratedNotification($report)
            );
        }
    }

    public function failed(Throwable $exception): void
    {
        Report::query()
            ->where('patient_id', $this->patientId)
            ->where('type', $this->type)
            ->where('status', ReportStatus::Processing)
            ->latest('id')
            ->first()
            ?->update([
                'status' => ReportStatus::Failed,
                'error_message' => $exception->getMessage(),
            ]);

        logger()->error(
            'Patient report generation failed.',
            [
                'patient_id' => $this->patientId,
                'type' => $this->type->value,
                'error' => $exception->getMessage(),
            ]
        );
    }
}
