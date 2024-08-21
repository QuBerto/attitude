<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Discord\Discord;
use Discord\Parts\Channel\Message;


class ListenToDiscord extends Command
{
    protected $signature = 'discord:start';
    protected $description = 'Start the Discord bot';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting Discord bot...');
        exec('node ' . base_path('discord-bot/bot.js'));
        return Command::SUCCESS;
    }
}
