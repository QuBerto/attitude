<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\UpdatePlayerMeta;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ProcessNpcKill;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::command('capture:screen')->everyFifteenMinutes();
Schedule::command('app:sync')->everySixHours();
Schedule::command('fetch:osrs-items')->everySixHours();
 // Schedule the 'sync:discord-rsaccounts' command to run daily at 00:30
 Schedule::command('generate:tokens')->dailyAt('00:11');

Schedule::command('sync:competitions 5260')->dailyAt('00:12');
Schedule::command('sync:playermeta')->everyMinute();
