<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\FollowUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class FollowUpDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly FollowUp $followUp,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'follow_up_id' => $this->followUp->id,
            'title' => $this->followUp->title,
            'due_at' => $this->followUp->due_at->toIso8601String(),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('یادآوری پیگیری بیمار')
            ->line($this->followUp->title)
            ->line(
                'زمان پیگیری: ' .
                $this->followUp->due_at->format('Y-m-d H:i')
            );
    }
}
