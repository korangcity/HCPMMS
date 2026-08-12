<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class PatientDoctorAssigned
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Patient $patient,
        public readonly User $doctor,
    ) {
    }
}
