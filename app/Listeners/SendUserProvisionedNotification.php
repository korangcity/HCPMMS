<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserProvisioned;
use App\Notifications\UserProvisionedNotification;

final class SendUserProvisionedNotification
{
    public function handle(UserProvisioned $event): void
    {
        $event->user->notify(
            new UserProvisionedNotification()
        );
    }
}
