<?php

declare(strict_types=1);

namespace App\Enums;

enum DoctorSpecialty: string
{
    case GENERAL_PRACTICE = 'general_practice';
    case INTERNAL_MEDICINE = 'internal_medicine';
    case CARDIOLOGY = 'cardiology';
    case ENDOCRINOLOGY = 'endocrinology';
    case NEUROLOGY = 'neurology';
    case PULMONOLOGY = 'pulmonology';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::GENERAL_PRACTICE => 'پزشکی عمومی',
            self::INTERNAL_MEDICINE => 'داخلی',
            self::CARDIOLOGY => 'قلب و عروق',
            self::ENDOCRINOLOGY => 'غدد',
            self::NEUROLOGY => 'مغز و اعصاب',
            self::PULMONOLOGY => 'ریه',
            self::OTHER => 'سایر',
        };
    }

    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            function (array $carry, self $specialty): array {
                $carry[$specialty->value] = $specialty->label();

                return $carry;
            },
            [],
        );
    }
}
