<?php

declare(strict_types=1);

namespace App\Enums;

enum PatientRelationStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'فعال',
            self::Inactive => 'غیرفعال',
        };
    }
}
