<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

final class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'admin',
            'doctor',
            'caregiver',
        ]);
    }

    public function view(User $user, Patient $patient): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($patient->user_id === $user->id) {
            return true;
        }

        if ($patient->doctors()
            ->whereKey($user->id)
            ->exists()) {
            return true;
        }

        return $patient->caregivers()
            ->whereKey($user->id)
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'admin',
            'doctor',
        ]);
    }

    public function update(User $user, Patient $patient): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $patient->user_id === $user->id
            || $patient->doctors()->whereKey($user->id)->exists();
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->hasRole('admin');
    }

    public function manageMedicalRecords(
        User $user,
        Patient $patient,
    ): bool {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $patient->doctors()
            ->whereKey($user->id)
            ->exists();
    }

    public function manageCaregivers(
        User $user,
        Patient $patient,
    ): bool {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $patient->doctors()
            ->whereKey($user->id)
            ->exists();
    }
}
