<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\AlertCreated;
use App\Models\AlertRecipient;
use App\Notifications\AlertNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

final class SendAlertNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;

    public int $backoff = 30;

    public function handle(AlertCreated $event): void
    {
        $alert = $event->alert;

        $alert->loadMissing([
            'recipients.user',
        ]);

        foreach ($alert->recipients as $recipient) {
            if (! $recipient instanceof AlertRecipient) {
                continue;
            }

            $recipient->user->notify(
                new AlertNotification($alert),
            );

            $recipient->update([
                'notified_at' => now(),
            ]);
        }
    }

    public function failed(
        AlertCreated $event,
        Throwable $exception,
    ): void {
        logger()->error(
            'Alert notification failed.',
            [
                'alert_id' => $event->alert->id,
                'error' => $exception->getMessage(),
            ],
        );
    }
}
