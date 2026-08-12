<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

final class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('patients.view');
    }

    public function view(User $user, Patient $patient): bool
    {
        if ($user->can('patients.view')) {
            return true;
        }

        if ($user->patient?->is($patient)) {
            return true;
        }

        if (
            $user->doctor !== null &&
            $user->doctor->patients()
                ->whereKey($patient->id)
                ->exists()
        ) {
            return true;
        }

        return $user->caregiver !== null &&
            $user->caregiver->patients()
                ->whereKey($patient->id)
                ->exists();
    }

    public function create(User $user): bool
    {
        return $user->can('patients.create');
    }

    public function update(User $user, Patient $patient): bool
    {
        if ($user->can('patients.update')) {
            return true;
        }

        return $user->patient?->is($patient) ?? false;
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->can('patients.delete');
    }

    public function assignDoctor(User $user, Patient $patient): bool
    {
        return $user->can('patients.assign-doctor');
    }

    public function assignCaregiver(User $user, Patient $patient): bool
    {
        return $user->can('patients.assign-caregiver');
    }
}
