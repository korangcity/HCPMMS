<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class PatientAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Patient $patient,
        private readonly string $role,
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->fullName(),
            'role' => $this->role,
            'message' => sprintf(
                'بیمار %s به شما اختصاص داده شد.',
                $this->patient->fullName()
            ),
        ];
    }
}
