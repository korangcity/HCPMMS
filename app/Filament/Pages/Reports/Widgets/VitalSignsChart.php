<?php

declare(strict_types=1);

namespace App\Filament\Pages\Reports\Widgets;

use App\Services\ReportService;
use Filament\Widgets\ChartWidget;

final class VitalSignsChart extends ChartWidget
{
    public array $filters = [];

    protected ?string $heading =
        'سایر علائم حیاتی';

    protected function getData(): array
    {
        $service = app(
            ReportService::class,
        );

        $types = [
            'temperature' => 'دمای بدن',
            'heart_rate' => 'ضربان قلب',
            'oxygen_saturation' => 'اشباع اکسیژن',
        ];

        $data = [];

        foreach ($types as $type => $label) {
            $data[] = [
                'label' => $label,
                'value' => $service->measurementCount(
                    $this->filters,
                    $type,
                ),
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'تعداد اندازه‌گیری',
                    'data' => collect($data)
                        ->pluck('value')
                        ->all(),
                ],
            ],
            'labels' => collect($data)
                ->pluck('label')
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
