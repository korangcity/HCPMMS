<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportType: string
{
    case BloodPressure = 'blood_pressure';
    case BloodGlucose = 'blood_glucose';
    case Weight = 'weight';
    case VitalSigns = 'vital_signs';
    case TimeRange = 'time_range';

    public function label(): string
    {
        return match ($this) {
            self::BloodPressure => 'روند فشار خون',
            self::BloodGlucose => 'روند قند خون',
            self::Weight => 'روند وزن',
            self::VitalSigns => 'سایر علائم حیاتی',
            self::TimeRange => 'گزارش بازه زمانی',
        };
    }
}
