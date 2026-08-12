<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\HealthRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class HealthRecordCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private readonly HealthRecord $healthRecord,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'health_record_created',
            'health_record_id' => $this->healthRecord->id,
            'patient_id' => $this->healthRecord->patient_id,
            'title' => $this->healthRecord->title,
        ];
    }
}
