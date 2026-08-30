<?php

declare(strict_types=1);

namespace App\Filament\Pages\Reports\Widgets;

use App\Services\ReportService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class ReportStatsWidget extends StatsOverviewWidget
{
    public array $filters = [];

    protected function getStats(): array
    {
        $service = app(ReportService::class);

        return [
            Stat::make(
                'تعداد پرونده‌های سلامت',
                number_format(
                    $service->count(
                        $this->filters,
                    ),
                ),
            )
                ->description('مطابق فیلترهای انتخاب‌شده')
                ->icon('heroicon-o-document-text'),

            Stat::make(
                'فشار خون',
                number_format(
                    $service->measurementCount(
                        $this->filters,
                        'blood_pressure',
                    ),
                ),
            )
                ->description('تعداد اندازه‌گیری')
                ->icon('heroicon-o-heart'),

            Stat::make(
                'قند خون',
                number_format(
                    $service->measurementCount(
                        $this->filters,
                        'blood_glucose',
                    ),
                ),
            )
                ->description('تعداد اندازه‌گیری')
                ->icon('heroicon-o-beaker'),

            Stat::make(
                'وزن',
                number_format(
                    $service->measurementCount(
                        $this->filters,
                        'weight',
                    ),
                ),
            )
                ->description('تعداد اندازه‌گیری')
                ->icon('heroicon-o-scale'),
        ];
    }
}
