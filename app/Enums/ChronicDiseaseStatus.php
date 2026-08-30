<?php

declare(strict_types=1);

namespace App\Enums;

enum ChronicDiseaseStatus: string
{
    case Active = 'active';
    case Controlled = 'controlled';
    case Remission = 'remission';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'فعال',
            self::Controlled => 'کنترل‌شده',
            self::Remission => 'در حالت خاموشی',
            self::Inactive => 'غیرفعال',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn (self $status): array => [
                    $status->value => $status->label(),
                ],
            )
            ->all();
    }
}
