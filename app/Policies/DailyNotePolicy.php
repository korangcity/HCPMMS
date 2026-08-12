<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DailyNote;
use App\Models\Patient;
use App\Models\User;

final class DailyNotePolicy
{
    /**
     * Determine whether the user can view any daily notes.
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isDoctor($user)
            || $this->isCaregiver($user)
            || $this->isPatient($user);
    }

    /**
     * Determine whether the user can view the daily note.
     */
    public function view(
        User $user,
        DailyNote $dailyNote,
    ): bool {
        return $this->canAccessPatient(
            $user,
            $dailyNote->patient,
        );
    }

    /**
     * Determine whether the user can create a daily note.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isDoctor($user)
            || $this->isCaregiver($user)
            || $this->isPatient($user);
    }

    /**
     * Determine whether the user can update the daily note.
     */
    public function update(
        User $user,
        DailyNote $dailyNote,
    ): bool {
        if ($this->isAdmin($user)) {
            return true;
        }

        // بیمار فقط یادداشت خودش را می‌تواند ویرایش کند.
        if ($this->isPatient($user)) {
            return $dailyNote->patient->user_id === $user->id
                && $dailyNote->created_by === $user->id;
        }

        // پزشک فقط یادداشت بیماران خودش را می‌تواند ویرایش کند.
        if ($this->isDoctor($user)) {
            return $this->canAccessPatient(
                $user,
                $dailyNote->patient,
            );
        }

        // مراقب فقط یادداشت بیماران مرتبط با خودش را می‌تواند ویرایش کند.
        if ($this->isCaregiver($user)) {
            return $this->canAccessPatient(
                $user,
                $dailyNote->patient,
            );
        }

        return false;
    }

    /**
     * Determine whether the user can delete the daily note.
     */
    public function delete(
        User $user,
        DailyNote $dailyNote,
    ): bool {
        if ($this->isAdmin($user)) {
            return true;
        }

        // ایجادکننده می‌تواند یادداشت خودش را حذف کند.
        if ($dailyNote->created_by === $user->id) {
            return $this->canAccessPatient(
                $user,
                $dailyNote->patient,
            );
        }

        return false;
    }

    /**
     * Determine whether the user can restore the daily note.
     */
    public function restore(
        User $user,
        DailyNote $dailyNote,
    ): bool {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete
     * the daily note.
     */
    public function forceDelete(
        User $user,
        DailyNote $dailyNote,
    ): bool {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can access the patient's
     * daily notes.
     */
    private function canAccessPatient(
        User $user,
        Patient $patient,
    ): bool {
        if ($this->isAdmin($user)) {
            return true;
        }

        // بیمار فقط اطلاعات خودش را می‌بیند.
        if ($this->isPatient($user)) {
            return $patient->user_id === $user->id;
        }

        // پزشک فقط بیماران اختصاص داده شده به خودش.
        if ($this->isDoctor($user)) {
            return $patient->doctors()
                ->whereKey($user->id)
                ->exists();
        }

        // مراقب فقط بیماران اختصاص داده شده به خودش.
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
