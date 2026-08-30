<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\ChartWidget;

final class WeightChart extends ChartWidget
{
    protected ?string $heading =
        'روند وزن';

    protected ?string $description =
        'میانگین وزن ثبت‌شده در ۳۰ روز اخیر';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return app(DashboardService::class)
            ->measurementTrend(
                'weight',
                30,
            );
    }

    protected function getType(): string
    {
        return 'line';
    }
}
