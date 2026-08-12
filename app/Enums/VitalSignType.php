<?php

declare(strict_types=1);

namespace App\Enums;

enum VitalSignType: string
{
    case BloodPressure = 'blood_pressure';
    case HeartRate = 'heart_rate';
    case RespiratoryRate = 'respiratory_rate';
    case BodyTemperature = 'body_temperature';
    case OxygenSaturation = 'oxygen_saturation';
    case BloodGlucose = 'blood_glucose';
    case Weight = 'weight';

    public function label(): string
    {
        return match ($this) {
            self::BloodPressure => 'فشار خون',
            self::HeartRate => 'ضربان قلب',
            self::RespiratoryRate => 'تعداد تنفس',
            self::BodyTemperature => 'دمای بدن',
            self::OxygenSaturation => 'اشباع اکسیژن',
            self::BloodGlucose => 'قند خون',
            self::Weight => 'وزن',
        };
    }

    public function unit(): string
    {
        return match ($this) {
            self::BloodPressure => 'mmHg',
            self::HeartRate => 'bpm',
            self::RespiratoryRate => 'breaths/min',
            self::BodyTemperature => '°C',
            self::OxygenSaturation => '%',
            self::BloodGlucose => 'mg/dL',
            self::Weight => 'kg',
        };
    }
}
