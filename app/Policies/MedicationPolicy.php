<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Medication;
use App\Models\User;

final class MedicationPolicy
{
    /**
     * Determine whether the user can view any medications.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'admin',
            'doctor',
            'patient',
            'caregiver',
        ]);
    }

    /**
     * Determine whether the user can view the medication.
     */
    public function view(User $user, Medication $medication): bool
    {
        return $user->hasAnyRole([
            'admin',
            'doctor',
            'patient',
            'caregiver',
        ]);
    }

    /**
     * Determine whether the user can create medications.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the medication.
     */
    public function update(User $user, Medication $medication): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the medication.
     */
    public function delete(User $user, Medication $medication): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the medication.
     */
    public function restore(User $user, Medication $medication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the medication.
     */
    public function forceDelete(User $user, Medication $medication): bool
    {
        return false;
    }
}
