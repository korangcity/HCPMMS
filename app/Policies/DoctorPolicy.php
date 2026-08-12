<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;

final class DoctorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('doctors.view');
    }

    public function view(User $user, Doctor $doctor): bool
    {
        return $user->can('doctors.view')
            || $user->doctor?->is($doctor);
    }

    public function create(User $user): bool
    {
        return $user->can('doctors.create');
    }

    public function update(User $user, Doctor $doctor): bool
    {
        return $user->can('doctors.update')
            || $user->doctor?->is($doctor);
    }

    public function delete(User $user, Doctor $doctor): bool
    {
        return $user->can('doctors.delete');
    }
}
