<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MedicationSchedule;
use App\Models\User;

final class MedicationSchedulePolicy
{
    /**
     * Determine whether the user can view any medication schedules.
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
     * Determine whether the user can view the medication schedule.
     */
    public function view(
        User $user,
        MedicationSchedule $medicationSchedule
    ): bool {
        $prescription = $medicationSchedule
            ->prescriptionItem
            ->prescription;

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
     * Determine whether the user can create medication schedules.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'admin',
            'doctor',
        ]);
    }

    /**
     * Determine whether the user can update the medication schedule.
     */
    public function update(
        User $user,
        MedicationSchedule $medicationSchedule
    ): bool {
        $prescription = $medicationSchedule
            ->prescriptionItem
            ->prescription;

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('doctor')
            && $prescription->doctor_id === $user->id
            && $prescription->isActive();
    }

    /**
     * Determine whether the user can delete the medication schedule.
     */
    public function delete(
        User $user,
        MedicationSchedule $medicationSchedule
    ): bool {
        $prescription = $medicationSchedule
            ->prescriptionItem
            ->prescription;

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('doctor')
            && $prescription->doctor_id === $user->id
            && $prescription->isActive();
    }

    /**
     * Determine whether the user can restore the medication schedule.
     */
    public function restore(
        User $user,
        MedicationSchedule $medicationSchedule
    ): bool {
        return false;
    }

    /**
     * Determine whether the user can permanently delete
     * the medication schedule.
     */
    public function forceDelete(
        User $user,
        MedicationSchedule $medicationSchedule
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
