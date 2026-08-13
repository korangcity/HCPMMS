<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\AlertStatus;
use App\Models\Alert;
use App\Models\User;

final class AlertPolicy
{
    public function view(User $user, Alert $alert): bool
    {
        return $this->hasAccessToPatient($user, $alert);
    }

    public function acknowledge(User $user, Alert $alert): bool
    {
        return $this->hasAccessToPatient($user, $alert)
            && $alert->status !== AlertStatus::Resolved;
    }

    public function resolve(User $user, Alert $alert): bool
    {
        return $this->hasAccessToPatient($user, $alert)
            && $alert->status !== AlertStatus::Resolved;
    }

    private function hasAccessToPatient(
        User $user,
        Alert $alert,
    ): bool {
        $patient = $alert->patient;

        return $patient->doctors()
                ->whereKey($user->id)
                ->exists()
            || $patient->caregivers()
                ->whereKey($user->id)
                ->exists();
    }
}
