<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PrescriptionItem;
use App\Models\User;

final class PrescriptionItemPolicy
{
    /**
     * Determine whether the user can view any prescription items.
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
     * Determine whether the user can view the prescription item.
     */
    public function view(
        User $user,
        PrescriptionItem $prescriptionItem
    ): bool {
        $prescription = $prescriptionItem->prescription;

        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('doctor')) {
            return $prescription->doctor_id === $user->id;
        }

        if ($user->hasRole('patient')) {
            return $prescription->patient->user_id === $user->id;
        }

        if ($user->hasRole('caregiver')) {
            return $this->isCaregiverOfPatient(
                $user,
                $prescription->patient
            );
        }

        return false;
    }

    /**
     * Determine whether the user can create prescription items.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'admin',
            'doctor',
        ]);
    }

    /**
     * Determine whether the user can update the prescription item.
     */
    public function update(
        User $user,
        PrescriptionItem $prescriptionItem
    ): bool {
        $prescription = $prescriptionItem->prescription;

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('doctor')
            && $prescription->doctor_id === $user->id
            && $prescription->isActive();
    }

    /**
     * Determine whether the user can delete the prescription item.
     */
    public function delete(
        User $user,
        PrescriptionItem $prescriptionItem
    ): bool {
        $prescription = $prescriptionItem->prescription;

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('doctor')
            && $prescription->doctor_id === $user->id
            && $prescription->isActive();
    }

    /**
     * Determine whether the user can restore the prescription item.
     */
    public function restore(
        User $user,
        PrescriptionItem $prescriptionItem
    ): bool {
        return false;
    }

    /**
     * Determine whether the user can permanently delete
     * the prescription item.
     */
    public function forceDelete(
        User $user,
        PrescriptionItem $prescriptionItem
    ): bool {
        return false;
    }

    private function isCaregiverOfPatient(
        User $user,
        object $patient
    ): bool {
        return $patient->caregivers()
            ->where('users.id', $user->id)
            ->exists();
    }
}
