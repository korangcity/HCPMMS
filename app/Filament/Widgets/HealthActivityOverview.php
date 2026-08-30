<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

final class HealthActivityOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $service = app(DashboardService::class);

        return [
            Stat::make(
                'نوبت‌ها',
                number_format(
                    $service->appointmentsCount(),
                ),
            )
                ->description('کل نوبت‌های ثبت‌شده')
                ->descriptionIcon(
                    Heroicon::OutlinedCalendarDays,
                )
                ->color('primary'),

            Stat::make(
                'بیماری‌های مزمن',
                number_format(
                    $service->diseasesCount(),
                ),
            )
                ->description('بیماری‌های ثبت‌شده')
                ->descriptionIcon(
                    Heroicon::OutlinedHeart,
                )
                ->color('warning'),

            Stat::make(
                'اندازه‌گیری‌ها',
                number_format(
                    $service->measurementsCount(),
                ),
            )
                ->description('اندازه‌گیری‌های ثبت‌شده')
                ->descriptionIcon(
                    Heroicon::OutlinedChartBar,
                )
                ->color('info'),

//            Stat::make(
//                'هشدارها',
////                number_format(
////                    $service->alertsCount(),
////                ),
//            )
//                ->description('هشدارهای سیستم')
////                ->descriptionIcon(
////                    Heroicon::OutlinedExclamationTriangle,
////                )
//                ->color('danger'),
        ];
    }
}
