<?php

namespace App\Providers;

use App\Events\AlertCreated;
use App\Events\FollowUpDue;
use App\Events\ReportGenerated;
use App\Events\UserProvisioned;
use App\Events\VitalSignRecorded;
use App\Listeners\DetectAbnormalVitalSign;
use App\Listeners\NotifyReportGenerated;
use App\Listeners\SendAlertNotification;
use App\Listeners\SendFollowUpDueNotification;
use App\Listeners\SendUserProvisionedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            UserProvisioned::class,
            SendUserProvisionedNotification::class
        );

        Event::listen(
            AlertCreated::class,
            SendAlertNotification::class,
        );

        Event::listen(
            VitalSignRecorded::class,
            DetectAbnormalVitalSign::class,
        );

        Event::listen(
            FollowUpDue::class,
            SendFollowUpDueNotification::class
        );

        Event::listen(
            ReportGenerated::class,
            NotifyReportGenerated::class
        );
    }
}
