<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ReportGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Report $report,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'patient_id' => $this->report->patient_id,
            'type' => $this->report->type->value,
            'title' => 'گزارش سلامت آماده شد',
            'message' => sprintf(
                'گزارش %s برای بیمار آماده شد.',
                $this->report->type->label()
            ),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('گزارش سلامت بیمار آماده شد')
            ->line(
                sprintf(
                    'گزارش %s برای بیمار آماده شده است.',
                    $this->report->type->label()
                )
            );
    }
}
