<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\MedicationSchedule;
use App\Notifications\MedicationReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

final class SendMedicationReminders extends Command
{
    protected $signature = 'medications:send-reminders
                            {--date= : Date in Y-m-d format}
                            {--time= : Time in H:i format}';

    protected $description = 'Send medication reminders for scheduled medications';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::createFromFormat('Y-m-d', (string) $this->option('date'))
            : now();

        $time = $this->option('time')
            ? (string) $this->option('time')
            : now()->format('H:i');

        $schedules = MedicationSchedule::query()
            ->with([
                'prescriptionItem.medication',
                'prescriptionItem.prescription.patient.user',
            ])
            ->active()
            ->where('scheduled_time', '>=', "{$time}:00")
            ->where('scheduled_time', '<', "{$time}:01")
            ->get();

        $sent = 0;

        foreach ($schedules as $schedule) {
            if (!$schedule->isActiveFor($date)) {
                continue;
            }

            $patient = $schedule
                ->prescriptionItem
                ->prescription
                ->patient;

            if (!$patient->user) {
                continue;
            }

            $patient->user->notify(
                new MedicationReminderNotification($schedule)
            );

            $sent++;
        }

        $this->info("Sent {$sent} medication reminders.");

        return self::SUCCESS;
    }
}
