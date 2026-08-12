<?php

declare(strict_types=1);

namespace App\Enums;

enum HealthReportStatus: string
{
    case Draft = 'draft';
    case Generated = 'generated';
    case Reviewed = 'reviewed';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'پیش‌نویس',
            self::Generated => 'تولید شده',
            self::Reviewed => 'بررسی شده',
            self::Archived => 'بایگانی شده',
        };
    }
}
