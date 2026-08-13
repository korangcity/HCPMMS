<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\FollowUp;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class FollowUpDue
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly FollowUp $followUp,
    ) {}
}
