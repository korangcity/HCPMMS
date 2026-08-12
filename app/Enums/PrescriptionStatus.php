<?php

declare(strict_types=1);

namespace App\Enums;

enum PrescriptionStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
