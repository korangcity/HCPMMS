<?php

declare(strict_types=1);

namespace App\Filament\Pages\Reports\Widgets;

use App\Services\ReportService;
use Filament\Widgets\ChartWidget;

final class BloodPressureChart extends ChartWidget
{
    public array $filters = [];

    protected ?string $heading =
        'روند فشار خون';

    protected function getData(): array
    {
        $measurements = app(
            ReportService::class,
        )->measurements(
            $this->filters,
            'blood_pressure',
        );

        $labels = [];

        $systolic = [];

        $diastolic = [];

        foreach ($measurements as $measurement) {
            $value = (string) $measurement['value'];

            if (! str_contains($value, '/')) {
                continue;
            }

            [$sys, $dia] = array_pad(
                explode('/', $value, 2),
                2,
                null,
            );

            if (! is_numeric($sys) || ! is_numeric($dia)) {
                continue;
            }

            $labels[] = filled(
                $measurement['recorded_at'],
            )
                ? jalaliDate(
                    $measurement['recorded_at'],
                )
                : '—';

            $systolic[] = (float) $sys;

            $diastolic[] = (float) $dia;
        }

        return [
            'datasets' => [
                [
                    'label' => 'سیستولیک',
                    'data' => $systolic,
                ],
                [
                    'label' => 'دیاستولیک',
                    'data' => $diastolic,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
