<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

final class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isMedicalStaff($user);
    }

    public function view(User $user, Report $report): bool
    {
        if ($user->id === $report->patient?->user_id) {
            return true;
        }

        if ($user->id === $report->patient?->doctor_id) {
            return true;
        }

        return $report->patient
            ->caregivers()
            ->whereKey($user->id)
            ->exists();
    }

    public function create(User $user): bool
    {
        return $this->isMedicalStaff($user);
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->id === $report->generated_by;
    }

    private function isMedicalStaff(User $user): bool
    {
        return $user->hasAnyRole([
            'doctor',
            'caregiver',
            'admin',
        ]);
    }
}
