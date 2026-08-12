<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\User;

final class HealthRecordPolicy
{
    /**
     * Determine whether the user can view any health records.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isDoctor($user)
            || $this->isCaregiver($user)
            || $this->isPatient($user);
    }

    /**
     * Determine whether the user can view the health record.
     */
    public function view(
        User $user,
        HealthRecord $healthRecord,
    ): bool {
        return $this->canAccessPatient(
            $user,
            $healthRecord->patient,
        );
    }

    /**
     * Determine whether the user can create health records.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isDoctor($user)
            || $this->isCaregiver($user);
    }

    /**
     * Determine whether the user can update the health record.
     */
    public function update(
        User $user,
        HealthRecord $healthRecord,
    ): bool {
        if ($this->isAdmin($user)) {
            return true;
        }

        if (
            ! $this->isDoctor($user)
            && ! $this->isCaregiver($user)
        ) {
            return false;
        }

        return $this->canAccessPatient(
            $user,
            $healthRecord->patient,
        );
    }

    /**
     * Determine whether the user can delete the health record.
     */
    public function delete(
        User $user,
        HealthRecord $healthRecord,
    ): bool {
        if ($this->isAdmin($user)) {
            return true;
        }

        // پزشک می‌تواند رکورد مربوط به بیمار خودش را حذف کند.
        if ($this->isDoctor($user)) {
            return $this->canAccessPatient(
                $user,
                $healthRecord->patient,
            );
        }

        return false;
    }

    /**
     * Determine whether the user can restore the health record.
     */
    public function restore(
        User $user,
        HealthRecord $healthRecord,
    ): bool {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete
     * the health record.
     */
    public function forceDelete(
        User $user,
        HealthRecord $healthRecord,
    ): bool {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can access patient's
     * health records.
     */
    private function canAccessPatient(
        User $user,
        Patient $patient,
    ): bool {
        if ($this->isAdmin($user)) {
            return true;
        }

        // بیمار فقط پرونده سلامت خودش را می‌بیند.
        if ($this->isPatient($user)) {
            return $patient->user_id === $user->id;
        }

        // پزشک فقط بیماران مرتبط با خودش را می‌بیند.
        if ($this->isDoctor($user)) {
            return $patient->doctors()
                ->whereKey($user->id)
                ->exists();
        }

        // مراقب فقط بیماران مرتبط با خودش را می‌بیند.
        if ($this->isCaregiver($user)) {
            return $patient->caregivers()
                ->whereKey($user->id)
                ->exists();
        }

        return false;
    }

    private function isAdmin(User $user): bool
    {
        return $user->role?->value === 'admin';
    }

    private function isDoctor(User $user): bool
    {
        return $user->role?->value === 'doctor';
    }

    private function isCaregiver(User $user): bool
    {
        return $user->role?->value === 'caregiver';
    }

    private function isPatient(User $user): bool
    {
        return $user->role?->value === 'patient';
    }
}
