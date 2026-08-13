<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

final class AppointmentStatsWidget extends ChartWidget
{
    protected ?string $heading = 'وضعیت ویزیت‌ها';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'ویزیت',
                    'data' => [
                        Appointment::query()
                            ->where('status', 'scheduled')
                            ->count(),

                        Appointment::query()
                            ->where('status', 'completed')
                            ->count(),

                        Appointment::query()
                            ->where('status', 'cancelled')
                            ->count(),
                    ],
                ],
            ],
            'labels' => [
                'برنامه‌ریزی شده',
                'انجام شده',
                'لغو شده',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
