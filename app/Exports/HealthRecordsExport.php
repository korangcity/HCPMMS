<?php

declare(strict_types=1);

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

final class HealthRecordsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private readonly array $filters,
    ) {
    }

    public function collection(): Collection
    {
        return app(
            ReportService::class,
        )->records(
            $this->filters,
        );
    }

    public function headings(): array
    {
        return [
            'بیمار',
            'بیماری مزمن',
            'پزشک / ثبت‌کننده',
            'نوبت',
            'ویزیت',
            'اندازه‌گیری‌ها',
            'داروهای تجویز شده',
            'توضیحات',
            'تاریخ ثبت',
        ];
    }

    public function map(
        $record,
    ): array {
        return [
            $record->patient?->user?->name
            ?? '—',

            $record->chronicDisease?->name
            ?? '—',

            $record->recorder?->name
            ?? '—',

            $record->appointment_id
                ? 'دارد'
                : '—',

            $record->visit_id
                ? 'دارد'
                : '—',

            $this->formatMeasurements(
                $record->measurement_information,
            ),

            $this->formatMedications(
                $record->medication_information,
            ),

            $record->description
            ?? '—',

            $record->created_at
                ? jalaliDate(
                $record->created_at,
            )
                : '—',
        ];
    }

    private function formatMeasurements(
        ?array $measurements,
    ): string {
        return collect($measurements ?? [])
            ->map(
                function (
                    array $measurement,
                ): string {
                    $type = match (
                        $measurement['type'] ?? null
                    ) {
                        'blood_pressure'
                        => 'فشار خون',

                        'blood_glucose'
                        => 'قند خون',

                        'weight'
                        => 'وزن',

                        'temperature'
                        => 'دمای بدن',

                        'heart_rate'
                        => 'ضربان قلب',

                        'oxygen_saturation'
                        => 'اشباع اکسیژن',

                        default
                        => 'نامشخص',
                    };

                    return sprintf(
                        '%s: %s %s',
                        $type,
                        $measurement['value'] ?? '—',
                        $measurement['unit'] ?? '',
                    );
                },
            )
            ->implode(' | ');
    }

    private function formatMedications(
        ?array $medications,
    ): string {
        return collect($medications ?? [])
            ->map(
                function (
                    array $medication,
                ): string {
                    return sprintf(
                        '%s - دوز: %s - مصرف: %s - دفعات: %s - شروع: %s - پایان: %s',
                        $medication['medication'] ?? '—',
                        $medication['dosage'] ?? '—',
                        $medication['administration_method'] ?? '—',
                        $medication['frequency'] ?? '—',
                        $medication['start_date'] ?? '—',
                        $medication['end_date'] ?? '—',
                    );
                },
            )
            ->implode(' | ');
    }
}
