<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\AlertPriority;
use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class AlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        private readonly Alert $alert,
    ) {}

    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'alert_id' => $this->alert->id,
            'patient_id' => $this->alert->patient_id,
            'type' => $this->alert->type->value,
            'priority' => $this->alert->priority->value,
            'status' => $this->alert->status->value,
            'title' => $this->alert->title,
            'message' => $this->alert->message,
            'triggered_at' => $this->alert->triggered_at?->toIso8601String(),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage())
            ->subject($this->alert->title)
            ->greeting('هشدار سلامت بیمار')
            ->line($this->alert->message)
            ->line(
                'اولویت: ' .
                $this->alert->priority->label(),
            );

        if ($this->alert->priority === AlertPriority::Critical) {
            $mail->line('این هشدار دارای اولویت بحرانی است.');
        }

        return $mail;
    }
}
