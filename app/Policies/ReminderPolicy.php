<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reminder;
use App\Models\User;

final class ReminderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('reminders.view');
    }

    public function view(User $user, Reminder $reminder): bool
    {
        return $user->can('reminders.view')
            && $user->canAccessPatient($reminder->patient_id);
    }

    public function create(User $user): bool
    {
        return $user->can('reminders.create');
    }

    public function update(User $user, Reminder $reminder): bool
    {
        return $user->can('reminders.update')
            && $user->canAccessPatient($reminder->patient_id);
    }

    public function delete(User $user, Reminder $reminder): bool
    {
        return $user->can('reminders.delete')
            && $user->canAccessPatient($reminder->patient_id);
    }

    public function complete(User $user, Reminder $reminder): bool
    {
        return $user->can('reminders.complete')
            && $user->canAccessPatient($reminder->patient_id);
    }
}
