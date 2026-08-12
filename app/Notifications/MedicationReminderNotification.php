<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\MedicationSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class MedicationReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private readonly MedicationSchedule $schedule,
    ) {}

    /**
     * @return array<int,string>
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $item = $this->schedule->prescriptionItem;
        $medication = $item->medication;

        return (new MailMessage())
            ->subject('Medication Reminder')
            ->line("Time to take {$medication->name}.")
            ->line("Dose: {$item->dose} {$item->dose_unit->value}")
            ->line("Scheduled time: {$this->schedule->scheduled_time}");
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(object $notifiable): array
    {
        $item = $this->schedule->prescriptionItem;
        $medication = $item->medication;

        return [
            'type' => 'medication_reminder',
            'schedule_id' => $this->schedule->id,
            'prescription_item_id' => $item->id,
            'medication_id' => $medication->id,
            'medication_name' => $medication->name,
            'dose' => $item->dose,
            'dose_unit' => $item->dose_unit->value,
            'scheduled_time' => $this->schedule->scheduled_time,
        ];
    }
}
