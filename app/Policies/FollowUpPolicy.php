<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FollowUp;
use App\Models\User;

final class FollowUpPolicy
{
    public function view(User $user, FollowUp $followUp): bool
    {
        return $user->isAdmin()
            || $user->patient?->id === $followUp->patient_id
            || $user->doctor?->id === $followUp->doctor_id
            || $user->caregiver?->patient_id === $followUp->patient_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin()
            || $user->doctor !== null;
    }

    public function update(User $user, FollowUp $followUp): bool
    {
        return $user->isAdmin()
            || $user->doctor?->id === $followUp->doctor_id;
    }
}
