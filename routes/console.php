<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Security cleanup - run daily at 2:00 AM
Schedule::command('security:cleanup')->dailyAt('02:00');

// Database backup - run daily at 3:00 AM
Schedule::command('database:backup')->dailyAt('03:00');

// Clear old log files - run weekly on Sundays
Schedule::command('log:clear --days=90')->weeklyOn(0, '04:00');



