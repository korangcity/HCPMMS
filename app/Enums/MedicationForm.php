<?php

declare(strict_types=1);

namespace App\Enums;

enum MedicationForm: string
{
    case Tablet = 'tablet';
    case Capsule = 'capsule';
    case Syrup = 'syrup';
    case Injection = 'injection';
    case Cream = 'cream';
    case Ointment = 'ointment';
    case Drop = 'drop';
    case Inhaler = 'inhaler';
    case Suppository = 'suppository';
    case Other = 'other';
}
