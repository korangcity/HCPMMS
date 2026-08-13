<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

final class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin()
            || $user->patient?->id === $appointment->patient_id
            || $user->doctor?->id === $appointment->doctor_id
            || $user->caregiver?->patient_id === $appointment->patient_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin()
            || $user->doctor !== null;
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin()
            || $user->doctor?->id === $appointment->doctor_id;
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $this->update($user, $appointment)
            && $appointment->isCancellable();
    }
}
