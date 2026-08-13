<?php

declare(strict_types=1);

namespace App\Enums;

enum AlertType: string
{
    case AbnormalVitalSign = 'abnormal_vital_sign';

    public function label(): string
    {
        return match ($this) {
            self::AbnormalVitalSign => 'مقدار غیرعادی علائم حیاتی',
        };
    }
}
