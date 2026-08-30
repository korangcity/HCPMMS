<?php

declare(strict_types=1);

namespace App\Filament\Pages\Reports\Widgets;

use App\Services\ReportService;
use Filament\Widgets\ChartWidget;

final class BloodGlucoseChart extends ChartWidget
{
    public array $filters = [];

    protected ?string $heading =
        'روند قند خون';

    protected function getData(): array
    {
        $measurements = app(
            ReportService::class,
        )->measurements(
            $this->filters,
            'blood_glucose',
        );

        return [
            'datasets' => [
                [
                    'label' => 'قند خون',
                    'data' => $measurements
                        ->map(
                            fn (
                                array $item,
                            ): float => (float) $item['value'],
                        )
                        ->values()
                        ->all(),
                ],
            ],
            'labels' => $measurements
                ->map(
                    fn (
                        array $item,
                    ): string => filled(
                        $item['recorded_at'],
                    )
                        ? jalaliDate(
                            $item['recorded_at'],
                        )
                        : '—',
                )
                ->values()
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
