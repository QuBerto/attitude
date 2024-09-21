<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;


class RunMultipleCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Syncing discord users...');
        Artisan::call('sync:discord-users');

        $this->info('Syncing wise old man...');
        Artisan::call('sync:wiseoldman-users 5260');

        $this->info('Syncing Emojis...');
        Artisan::call('sync:channels');


        $this->info('Syncing Discord & WOM...');
        Artisan::call('sync:discord-rsaccounts');

        $this->info('Syncing Emojis...');
        Artisan::call('sync:emojis');

        $this->info('Fetch osrs-items...');
        Artisan::call('fetch:osrs-items');

        $this->info('Fetch NPCS...');
        Artisan::call('fetch:osrs-npcs');
        return 0; // Indicate successful execution
    }
}
