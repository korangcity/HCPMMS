<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('patients:deactivate-expired-relations')
    ->dailyAt('00:10');



Schedule::command('medications:send-reminders')
    ->everyMinute()
    ->withoutOverlapping();



