<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\ChartWidget;

final class MeasurementTypesChart extends ChartWidget
{
    protected ?string $heading =
        'توزیع اندازه‌گیری‌ها';

    protected ?string $description =
        'تعداد اندازه‌گیری‌های ثبت‌شده در ۳۰ روز اخیر';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return app(DashboardService::class)
            ->measurementTypes(30);
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
