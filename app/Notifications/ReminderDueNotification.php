<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ReminderDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private readonly Reminder $reminder,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reminder_id' => $this->reminder->id,
            'type' => $this->reminder->type->value,
            'title' => $this->reminder->title,
            'scheduled_at' => $this->reminder
                ->scheduled_at
                ->toIso8601String(),
        ];
    }

    public function failed(\Throwable $exception): void
    {
        logger()->error(
            'Reminder notification failed.',
            [
                'reminder_id' => $this->reminder->id,
                'error' => $exception->getMessage(),
            ],
        );
    }
}
