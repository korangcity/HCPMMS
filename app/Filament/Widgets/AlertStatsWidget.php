<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Alert;
use Filament\Widgets\ChartWidget;

final class AlertStatsWidget extends ChartWidget
{
    protected ?string $heading = 'هشدارهای ۷ روز اخیر';

    protected function getData(): array
    {
        $days = collect(range(6, 0))
            ->map(
                fn (int $day): string => today()
                    ->subDays($day)
                    ->format('Y-m-d')
            );

        $data = $days->map(
            fn (string $date): int => Alert::query()
                ->whereDate('created_at', $date)
                ->count()
        );

        return [
            'datasets' => [
                [
                    'label' => 'تعداد هشدار',
                    'data' => $data->values()->all(),
                ],
            ],
            'labels' => $days->map(
                fn (string $date): string =>
                date('m/d', strtotime($date))
            )->values()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
