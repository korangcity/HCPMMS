<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\AlertStatus;
use App\Models\Alert;
use Illuminate\Console\Command;

final class ProcessPendingAlerts extends Command
{
    protected $signature = 'alerts:process-pending';

    protected $description = 'Process and report pending alerts.';

    public function handle(): int
    {
        $count = Alert::query()
            ->where('status', AlertStatus::Open->value)
            ->where('triggered_at', '<=', now())
            ->count();

        $this->info(
            sprintf(
                '%d pending alerts found.',
                $count,
            ),
        );

        return self::SUCCESS;
    }
}
