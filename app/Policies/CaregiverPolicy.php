<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Caregiver;
use App\Models\User;

final class CaregiverPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('caregivers.view');
    }

    public function view(User $user, Caregiver $caregiver): bool
    {
        return $user->can('caregivers.view')
            || $user->caregiver?->is($caregiver);
    }

    public function create(User $user): bool
    {
        return $user->can('caregivers.create');
    }

    public function update(User $user, Caregiver $caregiver): bool
    {
        return $user->can('caregivers.update')
            || $user->caregiver?->is($caregiver);
    }

    public function delete(User $user, Caregiver $caregiver): bool
    {
        return $user->can('caregivers.delete');
    }
}
