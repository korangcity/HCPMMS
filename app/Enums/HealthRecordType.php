<?php

declare(strict_types=1);

namespace App\Enums;

enum HealthRecordType: string
{
    case Diagnosis = 'diagnosis';
    case Medication = 'medication';
    case Allergy = 'allergy';
    case Surgery = 'surgery';
    case Hospitalization = 'hospitalization';
    case Laboratory = 'laboratory';
    case Imaging = 'imaging';
    case Vaccination = 'vaccination';
    case Note = 'note';

    public function label(): string
    {
        return match ($this) {
            self::Diagnosis => 'تشخیص',
            self::Medication => 'دارو',
            self::Allergy => 'حساسیت',
            self::Surgery => 'جراحی',
            self::Hospitalization => 'بستری',
            self::Laboratory => 'آزمایش',
            self::Imaging => 'تصویربرداری',
            self::Vaccination => 'واکسیناسیون',
            self::Note => 'یادداشت پزشکی',
        };
    }
}
