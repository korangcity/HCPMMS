<?php

declare(strict_types=1);

namespace App\Enums;

enum CaregiverType: string
{
    case Family = 'family';
    case Professional = 'professional';
    case Nurse = 'nurse';
    case Other = 'other';
}
