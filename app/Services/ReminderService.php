<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ReminderStatus;
use App\Enums\ReminderType;
use App\Events\ReminderCompleted;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class ReminderService
{
    public function create(
        int $patientId,
        ReminderType $type,
        string $title,
        \DateTimeInterface $scheduledAt,
        ?string $description = null,
        ?int $medicationId = null,
    ): Reminder {
        if (
            $type === ReminderType::Medication
            && $medicationId === null
        ) {
            throw new InvalidArgumentException(
                'Medication reminder requires a medication.',
            );
        }

        if (
            $type !== ReminderType::Medication
            && $medicationId !== null
        ) {
            throw new InvalidArgumentException(
                'Only medication reminders may reference a medication.',
            );
        }

        return Reminder::query()->create([
            'patient_id' => $patientId,
            'medication_id' => $medicationId,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'scheduled_at' => $scheduledAt,
            'status' => ReminderStatus::Pending,
        ]);
    }

    public function complete(
        Reminder $reminder,
        User $user,
    ): Reminder {
        return DB::transaction(function () use (
            $reminder,
            $user,
        ): Reminder {
            $reminder->refresh();

            if (!$reminder->isPending()) {
                throw new InvalidArgumentException(
                    'Only pending reminders can be completed.',
                );
            }

            $reminder->update([
                'status' => ReminderStatus::Completed,
                'completed_at' => now(),
                'completed_by' => $user->id,
            ]);

            event(new ReminderCompleted($reminder));

            return $reminder->fresh([
                'patient',
                'medication',
                'completedBy',
            ]);
        });
    }

    public function cancel(Reminder $reminder): Reminder
    {
        if (!$reminder->isPending()) {
            throw new InvalidArgumentException(
                'Only pending reminders can be cancelled.',
            );
        }

        $reminder->update([
            'status' => ReminderStatus::Cancelled,
        ]);

        return $reminder->fresh();
    }

    public function markMissed(Reminder $reminder): Reminder
    {
        if (!$reminder->isPending()) {
            return $reminder;
        }

        $reminder->update([
            'status' => ReminderStatus::Missed,
        ]);

        return $reminder->fresh();
    }

    /**
     * @return int Number of reminders marked as missed.
     */
    public function markOverdueAsMissed(): int
    {
        return Reminder::query()
            ->due()
            ->where(
                'scheduled_at',
                '<',
                now()->subHour(),
            )
            ->update([
                'status' => ReminderStatus::Missed,
                'updated_at' => now(),
            ]);
    }
}
