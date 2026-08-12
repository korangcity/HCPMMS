<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use App\Models\VitalSign;

final class VitalSignPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->canAccessHealthData($user);
    }

    public function view(User $user, VitalSign $vitalSign): bool
    {
        return $this->canAccessPatient($user, $vitalSign->patient);
    }

    public function create(User $user, Patient $patient): bool
    {
        return $this->canAccessPatient($user, $patient);
    }

    public function update(User $user, VitalSign $vitalSign): bool
    {
        return $this->canAccessPatient($user, $vitalSign->patient);
    }

    public function delete(User $user, VitalSign $vitalSign): bool
    {
        return $this->canAccessPatient($user, $vitalSign->patient);
    }

    private function canAccessHealthData(User $user): bool
    {
        return $user->is_admin
            ?? false;
    }

    private function canAccessPatient(
        User $user,
        Patient $patient
    ): bool {
        if ($user->is_admin ?? false) {
            return true;
        }

        if ($patient->user_id === $user->id) {
            return true;
        }

        return $patient->doctors()
                ->whereKey($user->id)
                ->exists()
            || $patient->caregivers()
                ->whereKey($user->id)
                ->exists();
    }
}
