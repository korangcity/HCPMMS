<?php

declare(strict_types=1);

namespace App\Enums;

enum MedicationScheduleFrequency: string
{
    case OnceDaily = 'once_daily';
    case TwiceDaily = 'twice_daily';
    case ThreeTimesDaily = 'three_times_daily';
    case FourTimesDaily = 'four_times_daily';
    case EveryXHours = 'every_x_hours';
    case AsNeeded = 'as_needed';
    case Custom = 'custom';
}
