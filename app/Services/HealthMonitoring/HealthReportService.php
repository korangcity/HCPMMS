<?php

declare(strict_types=1);

namespace App\Services\HealthMonitoring;

use App\Enums\HealthReportStatus;
use App\Models\DailyNote;
use App\Models\HealthRecord;
use App\Models\HealthReport;
use App\Models\Patient;
use App\Models\VitalSign;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class HealthReportService
{
    public function generate(
        Patient $patient,
        Carbon $from,
        Carbon $to,
        ?int $generatedBy = null,
    ): HealthReport {
        return DB::transaction(function () use (
            $patient,
            $from,
            $to,
            $generatedBy,
        ): HealthReport {
            $vitalSigns = VitalSign::query()
                ->forPatient($patient->id)
                ->between($from, $to)
                ->with('recorder')
                ->orderBy('recorded_at')
                ->get();

            $healthRecords = HealthRecord::query()
                ->forPatient($patient->id)
                ->between($from, $to)
                ->with('recorder')
                ->orderBy('recorded_at')
                ->get();

            $dailyNotes = DailyNote::query()
                ->forPatient($patient->id)
                ->between($from->toDateString(), $to->toDateString())
                ->with('creator')
                ->orderBy('note_date')
                ->get();

            $summary = [
                'vital_signs' => [
                    'count' => $vitalSigns->count(),
                    'by_type' => $vitalSigns
                        ->groupBy(fn (VitalSign $vital): string => $vital->type->value)
                        ->map(fn ($items): int => $items->count())
                        ->toArray(),
                ],
                'health_records' => [
                    'count' => $healthRecords->count(),
                    'by_type' => $healthRecords
                        ->groupBy(fn (HealthRecord $record): string => $record->type->value)
                        ->map(fn ($items): int => $items->count())
                        ->toArray(),
                ],
                'daily_notes' => [
                    'count' => $dailyNotes->count(),
                ],
            ];

            $content = $this->buildContent(
                $vitalSigns,
                $healthRecords,
                $dailyNotes,
            );

            return HealthReport::query()->create([
                'patient_id' => $patient->id,
                'generated_by' => $generatedBy,
                'title' => sprintf(
                    'گزارش سلامت از %s تا %s',
                    $from->toDateString(),
                    $to->toDateString(),
                ),
                'period_start' => $from->toDateString(),
                'period_end' => $to->toDateString(),
                'status' => HealthReportStatus::Generated,
                'summary' => $summary,
                'content' => $content,
                'generated_at' => now(),
            ]);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection<int, VitalSign> $vitalSigns
     * @param \Illuminate\Database\Eloquent\Collection<int, HealthRecord> $healthRecords
     * @param \Illuminate\Database\Eloquent\Collection<int, DailyNote> $dailyNotes
     */
    private function buildContent(
        $vitalSigns,
        $healthRecords,
        $dailyNotes,
    ): string {
        $lines = [];

        $lines[] = 'خلاصه وضعیت سلامت';
        $lines[] = '';

        $lines[] = sprintf(
            'تعداد اندازه‌گیری علائم حیاتی: %d',
            $vitalSigns->count(),
        );

        $lines[] = sprintf(
            'تعداد سوابق سلامت: %d',
            $healthRecords->count(),
        );

        $lines[] = sprintf(
            'تعداد یادداشت‌های روزانه: %d',
            $dailyNotes->count(),
        );

        return implode(PHP_EOL, $lines);
    }
}
