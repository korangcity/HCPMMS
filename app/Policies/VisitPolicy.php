<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;

final class VisitPolicy
{
    public function view(User $user, Visit $visit): bool
    {
        return $user->isAdmin()
            || $user->patient?->id === $visit->patient_id
            || $user->doctor?->id === $visit->doctor_id
            || $user->caregiver?->patient_id === $visit->patient_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin()
            || $user->doctor !== null;
    }

    public function update(User $user, Visit $visit): bool
    {
        return $user->isAdmin()
            || $user->doctor?->id === $visit->doctor_id;
    }
}
