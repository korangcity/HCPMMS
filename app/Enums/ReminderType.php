<?php

declare(strict_types=1);

namespace App\Enums;

enum ReminderType: string
{
    case Medication = 'medication';
    case Appointment = 'appointment';
    case LabTest = 'lab_test';

    public function label(): string
    {
        return match ($this) {
            self::Medication => 'مصرف دارو',
            self::Appointment => 'ویزیت',
            self::LabTest => 'آزمایش',
        };
    }
}
