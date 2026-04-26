<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-purge site_visits older than 90 days daily at 03:00 AM
Schedule::command('analytics:purge-visits')->dailyAt('03:00');

// Auto-sync cybersec profiles (TryHackMe & LetsDefend) daily at 04:00 AM
Schedule::command('cybersec:sync')->dailyAt('04:00');
