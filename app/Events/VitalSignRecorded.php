<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\VitalSign;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class VitalSignRecorded implements ShouldDispatchAfterCommit
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly VitalSign $vitalSign,
    ) {}
}
