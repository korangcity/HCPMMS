<?php

declare(strict_types=1);

namespace App\Enums;

enum DoseUnit: string
{
    case Tablet = 'tablet';
    case Capsule = 'capsule';
    case Milligram = 'mg';
    case Gram = 'g';
    case Milliliter = 'ml';
    case Drop = 'drop';
    case Puff = 'puff';
    case Unit = 'unit';
}
