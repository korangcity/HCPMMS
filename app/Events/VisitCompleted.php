<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Visit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class VisitCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Visit $visit,
    ) {}
}
