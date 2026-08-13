<?php

declare(strict_types=1);

namespace App\Enums;

enum AlertPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'کم',
            self::Medium => 'متوسط',
            self::High => 'زیاد',
            self::Critical => 'بحرانی',
        };
    }

    public function score(): int
    {
        return match ($this) {
            self::Low => 1,
            self::Medium => 2,
            self::High => 3,
            self::Critical => 4,
        };
    }
}
