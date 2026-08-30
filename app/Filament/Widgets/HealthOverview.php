<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

final class HealthOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $service = app(DashboardService::class);

        return [
            Stat::make(
                'بیماران',
                number_format($service->patientsCount()),
            )
                ->description('تعداد بیماران ثبت‌شده')
                ->descriptionIcon(
                    Heroicon::OutlinedUserGroup,
                )
                ->color('primary'),

            Stat::make(
                'پزشکان',
                number_format($service->doctorsCount()),
            )
                ->description('پزشکان ثبت‌شده')
                ->descriptionIcon(
                    Heroicon::OutlinedUser,
                )
                ->color('success'),

            Stat::make(
                'پرونده‌های سلامت',
                number_format(
                    $service->healthRecordsCount(),
                ),
            )
                ->description('پرونده‌های ثبت‌شده')
                ->descriptionIcon(
                    Heroicon::OutlinedDocumentText,
                )
                ->color('info'),

            Stat::make(
                'ویزیت‌ها',
                number_format($service->visitsCount()),
            )
                ->description('ویزیت‌های ثبت‌شده')
                ->descriptionIcon(
                    Heroicon::OutlinedClipboardDocumentCheck,
                )
                ->color('success'),
        ];
    }
}
