<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Processing => 'در حال پردازش',
            self::Completed => 'تکمیل شده',
            self::Failed => 'ناموفق',
        };
    }
}
