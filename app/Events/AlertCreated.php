<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Alert;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AlertCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Alert $alert,
    ) {}
}
