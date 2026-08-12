<?php

declare(strict_types=1);

namespace App\Enums;

enum MedicationRoute: string
{
    case Oral = 'oral';
    case Sublingual = 'sublingual';
    case Topical = 'topical';
    case Intravenous = 'intravenous';
    case Intramuscular = 'intramuscular';
    case Subcutaneous = 'subcutaneous';
    case Inhalation = 'inhalation';
    case Ophthalmic = 'ophthalmic';
    case Otic = 'otic';
    case Rectal = 'rectal';
    case Other = 'other';
}
