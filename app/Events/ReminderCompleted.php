<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Reminder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ReminderCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Reminder $reminder,
    ) {}
}
