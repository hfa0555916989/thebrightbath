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

// Payments still pending after 15 minutes: ask the bank for their final state
Artisan::command('payments:reconcile', function (\App\Services\NeoleapService $neoleap) {
    $this->info('Resolved: '.$neoleap->reconcilePending());
})->purpose('Check pending gateway payments with the bank (inquiry)');

Schedule::command('payments:reconcile')->everyTenMinutes()->withoutOverlapping();

// Session reminders: 24 hours and 1 hour before, to client and consultant
Artisan::command('sessions:send-reminders', function (\App\Services\SessionReminderService $reminders) {
    $sent = $reminders->sendDue();
    $this->info("Sent: {$sent['24h']} (24h), {$sent['1h']} (1h)");
})->purpose('Email session reminders 24 hours and 1 hour before');

Schedule::command('sessions:send-reminders')->everyFiveMinutes()->withoutOverlapping();






