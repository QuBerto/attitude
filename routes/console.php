<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\UpdatePlayerMeta;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

 
// Schedule::command('emails:send Taylor --force')->daily();
 
// Schedule::command(UpdatePlayerMeta::class, ['Taylor', '--force'])->daily();