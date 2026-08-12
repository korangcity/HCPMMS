<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\HealthRecordCreated;
use App\Notifications\HealthRecordCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

final class NotifyHealthRecordCreated implements ShouldQueue
{
    public int $tries = 3;

    public int $backoff = 60;

    public function handle(HealthRecordCreated $event): void
    {
        $patient = $event->healthRecord
            ->loadMissing('patient.user');

        $user = $patient->patient->user;

        if ($user !== null) {
            $user->notify(
                new HealthRecordCreatedNotification(
                    $event->healthRecord,
                ),
            );
        }
    }
}
