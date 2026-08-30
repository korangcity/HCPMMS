<?php

declare(strict_types=1);

namespace App\Enums;

enum AppointmentStatus: string
{
    case Scheduled = 'scheduled';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => 'برنامه‌ریزی شده',
            self::Completed => 'انجام شده',
            self::Cancelled => 'لغو شده',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Scheduled => 'warning',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
