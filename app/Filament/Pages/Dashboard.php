<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\BloodGlucoseChart;
use App\Filament\Widgets\BloodPressureChart;
use App\Filament\Widgets\HealthActivityOverview;
use App\Filament\Widgets\HealthOverview;
use App\Filament\Widgets\MeasurementTypesChart;
use App\Filament\Widgets\WeightChart;
use Filament\Pages\Dashboard as BaseDashboard;

final class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            HealthOverview::class,
            HealthActivityOverview::class,

            BloodPressureChart::class,
            BloodGlucoseChart::class,

            WeightChart::class,
            MeasurementTypesChart::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 4,
        ];
    }
}
