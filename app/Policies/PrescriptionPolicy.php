<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;

final class PrescriptionPolicy
{
    public function view(User $user, Prescription $prescription): bool
    {
        return $prescription->doctor_id === $user->id
            || $prescription->patient->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    public function update(User $user, Prescription $prescription): bool
    {
        return $prescription->doctor_id === $user->id
            && $prescription->status->value !== 'cancelled';
    }

    public function delete(User $user, Prescription $prescription): bool
    {
        return false;
    }

    public function cancel(User $user, Prescription $prescription): bool
    {
        return $prescription->doctor_id === $user->id
            && $prescription->isActive();
    }
}
