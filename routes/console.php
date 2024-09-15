<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\UpdatePlayerMeta;
use Illuminate\Support\Facades\Schedule;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::command('capture:screen')->everyFifteenMinutes();
// Schedule the 'sync:discord-users' command to run daily at midnight
Schedule::command('sync:discord-users')->dailyAt('00:00');

// Schedule the 'sync:wiseoldman-users 5260' command to run daily at 00:15
Schedule::command('sync:wiseoldman-users 5260')->dailyAt('00:05');

// Schedule the 'sync:discord-rsaccounts' command to run daily at 00:30
Schedule::command('sync:discord-rsaccounts')->dailyAt('00:10');

 // Schedule the 'sync:discord-rsaccounts' command to run daily at 00:30
 Schedule::command('generate:tokens')->dailyAt('00:11');

Schedule::command('sync:competitions 5260')->dailyAt('00:12');
Schedule::command('sync:playermeta')->everyFiveMinutes();