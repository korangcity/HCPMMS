<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\FollowUpDue;
use App\Models\FollowUp;
use Illuminate\Console\Command;

final class ProcessDueFollowUps extends Command
{
    protected $signature = 'follow-ups:process-due';

    protected $description = 'Process due patient follow-ups';

    public function handle(): int
    {
        FollowUp::query()
            ->with([
                'patient',
                'doctor',
            ])
            ->whereNull('notified_at')
            ->where('due_at', '<=', now())
            ->where('status', 'pending')
            ->chunkById(100, function ($followUps): void {
                foreach ($followUps as $followUp) {
                    FollowUpDue::dispatch($followUp);
                }
            });

        return self::SUCCESS;
    }
}
