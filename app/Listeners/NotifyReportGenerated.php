<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ReportGenerated;
use App\Notifications\ReportGeneratedNotification;

final class NotifyReportGenerated
{
    public function handle(ReportGenerated $event): void
    {
        $report = $event->report;

        $report->loadMissing([
            'patient.user',
            'patient.doctor',
            'patient.caregivers',
        ]);

        $notification = new ReportGeneratedNotification($report);

        if ($report->patient?->user !== null) {
            $report->patient->user->notify($notification);
        }

        if ($report->patient?->doctor !== null) {
            $report->patient->doctor->notify($notification);
        }

        foreach ($report->patient?->caregivers ?? [] as $caregiver) {
            $caregiver->notify($notification);
        }
    }
}
