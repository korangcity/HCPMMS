<?php

namespace App\Providers;

use App\Events\UserProvisioned;
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
    }
}
