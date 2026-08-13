<?php

declare(strict_types=1);

namespace App\Enums;

enum AlertStatus: string
{
    case Open = 'open';
    case Acknowledged = 'acknowledged';
    case Resolved = 'resolved';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'باز',
            self::Acknowledged => 'تأیید شده',
            self::Resolved => 'برطرف شده',
        };
    }
}
