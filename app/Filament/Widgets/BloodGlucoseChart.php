<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\ChartWidget;

final class BloodGlucoseChart extends ChartWidget
{
    protected ?string $heading =
        'روند قند خون';

    protected ?string $description =
        'میانگین قند خون در ۳۰ روز اخیر';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        return app(DashboardService::class)
            ->measurementTrend(
                'blood_glucose',
                30,
            );
    }

    protected function getType(): string
    {
        return 'line';
    }
}
