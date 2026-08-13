<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Alert;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class PatientStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'بیماران',
                Patient::query()->count(),
            )
                ->description('تعداد کل بیماران')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make(
                'ویزیت‌های امروز',
                Appointment::query()
                    ->whereDate('scheduled_at', today())
                    ->count(),
            )
                ->description('برنامه ویزیت امروز')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info'),

            Stat::make(
                'هشدارهای باز',
                Alert::query()
                    ->where('status', 'open')
                    ->count(),
            )
                ->description('هشدارهای نیازمند پیگیری')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
