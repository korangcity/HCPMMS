<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DoctorNote;
use App\Models\User;

final class DoctorNotePolicy
{
    public function view(User $user, DoctorNote $note): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($note->is_private) {
            return $user->doctor?->id === $note->doctor_id;
        }

        return $user->doctor?->id === $note->doctor_id
            || $user->patient?->id === $note->visit->patient_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin()
            || $user->doctor !== null;
    }

    public function update(User $user, DoctorNote $note): bool
    {
        return $user->isAdmin()
            || $user->doctor?->id === $note->doctor_id;
    }

    public function delete(User $user, DoctorNote $note): bool
    {
        return $this->update($user, $note);
    }
}
