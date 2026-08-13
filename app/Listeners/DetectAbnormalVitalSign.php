<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\VitalSignRecorded;
use App\Models\VitalSign;
use App\Services\AlertService;

final class DetectAbnormalVitalSign
{
    public function __construct(
        private readonly AlertService $alertService,
    ) {}

    public function handle(VitalSignRecorded $event): void
    {
        $this->alertService->processVitalSign(
            $event->vitalSign,
        );
    }
}
