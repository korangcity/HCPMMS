<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ReminderStatus;
use App\Models\Reminder;
use App\Notifications\ReminderDueNotification;
use App\Services\ReminderService;
use Illuminate\Console\Command;

final class ProcessRemindersCommand extends Command
{
    protected $signature = 'reminders:process';

    protected $description = 'Process due and overdue patient reminders.';

    public function handle(ReminderService $service): int
    {
        $service->markOverdueAsMissed();

        Reminder::query()
            ->with([
                'patient.user',
                'medication',
            ])
            ->where('status', ReminderStatus::Pending)
            ->whereNull('notified_at')
            ->whereBetween(
                'scheduled_at',
                [
                    now()->subMinutes(5),
                    now()->addMinutes(5),
                ],
            )
            ->chunkById(100, function ($reminders): void {
                foreach ($reminders as $reminder) {
                    $notifiable = $reminder->patient->user;

                    if ($notifiable === null) {
                        continue;
                    }

                    $notifiable->notify(
                        new ReminderDueNotification($reminder),
                    );

                    $reminder->update([
                        'notified_at' => now(),
                    ]);
                }
            });

        $this->info('Reminders processed successfully.');

        return self::SUCCESS;
    }
}
