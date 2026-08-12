<?php

declare(strict_types=1);

namespace App\Enums;

enum HealthRecordType: string
{
    case Symptom = 'symptom';
    case Medication = 'medication';
    case Treatment = 'treatment';
    case DoctorVisit = 'doctor_visit';
    case Laboratory = 'laboratory';
    case Hospitalization = 'hospitalization';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Symptom => 'علائم',
            self::Medication => 'دارو',
            self::Treatment => 'درمان',
            self::DoctorVisit => 'ویزیت پزشک',
            self::Laboratory => 'آزمایش',
            self::Hospitalization => 'بستری',
            self::General => 'عمومی',
        };
    }
}
