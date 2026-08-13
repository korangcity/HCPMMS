<?php

declare(strict_types=1);

namespace App\Enums;

enum ReminderStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Missed = 'missed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Completed => 'انجام شده',
            self::Cancelled => 'لغو شده',
            self::Missed => 'از دست رفته',
        };
    }
}
