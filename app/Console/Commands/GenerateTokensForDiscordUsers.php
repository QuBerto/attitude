<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DiscordUser;
use Illuminate\Support\Str;

class GenerateTokensForDiscordUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tokens for Discord users without a token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all Discord users without a token
        $discordUsersWithoutToken = DiscordUser::whereNull('token')->get();

        // Generate a token for each user without a token
        foreach ($discordUsersWithoutToken as $user) {
            $user->token = Str::random(60); // Generate a 60-character token
            $user->save();
        }

        $this->info('Tokens generated for users without a token.');
        return 0;
    }
}
