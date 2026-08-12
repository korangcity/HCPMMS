<?php

declare(strict_types=1);

namespace App\Enums;

enum PatientGender: string
{
    case Male = 'male';
    case Female = 'female';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'مرد',
            self::Female => 'زن',
            self::Other => 'سایر',
        };
    }
}
