<?php

declare(strict_types=1);

namespace App\Enums;

enum AcademicDegree: string
{
    case GENERAL_PRACTITIONER = 'general_practitioner';
    case SPECIALIST = 'specialist';
    case SUBSPECIALIST = 'subspecialist';

    public function label(): string
    {
        return match ($this) {
            self::GENERAL_PRACTITIONER => 'پزشک عمومی',
            self::SPECIALIST => 'متخصص',
            self::SUBSPECIALIST => 'فوق تخصص',
        };
    }

    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            function (array $carry, self $degree): array {
                $carry[$degree->value] = $degree->label();

                return $carry;
            },
            [],
        );
    }
}
