<?php

declare(strict_types=1);

namespace App\Enums;

enum AppointmentStatus: string
{
    case Scheduled = 'scheduled';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => 'برنامه‌ریزی شده',
            self::Confirmed => 'تأیید شده',
            self::Completed => 'انجام شده',
            self::Cancelled => 'لغو شده',
            self::NoShow => 'عدم مراجعه',
        };
    }
}
