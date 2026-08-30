<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\ChartWidget;

final class BloodPressureChart extends ChartWidget
{
    protected ?string $heading =
        'روند فشار خون';

    protected ?string $description =
        'میانگین فشار سیستولیک و دیاستولیک در ۳۰ روز اخیر';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return app(DashboardService::class)
            ->bloodPressureTrend(30);
    }

    protected function getType(): string
    {
        return 'line';
    }
}
