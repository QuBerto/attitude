<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DiscordUser;
use App\Models\RSAccount;

class SyncDiscordAndRSAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:discord-rsaccounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Discord users with RSAccounts based on username';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all Discord users
        $discordUsers = DiscordUser::whereNotNull('nick')->get();

        foreach ($discordUsers as $discordUser) {
             // Define the pattern to match the icon and everything after it
              // Define the pattern to match the icon and everything after it
            // Define the pattern to match the icon and everything after it
            $pattern = '/\s*\p{So}[^\s]*\s*(\(.*?\))?/u';

            // Perform the replacement
            $participant = preg_replace($pattern, '', $discordUser->nick);

            // Output the modified nick
           
            // Find RSAccounts with the same username, case-insensitive
            $matchingAccounts = RSAccount::whereRaw('LOWER(username) = ?', [strtolower($participant)])
                ->orWhereRaw('LOWER(display_name) = ?', [strtolower($participant)])
                ->get();
                $this->info('Discord user: ' . trim($participant));
            foreach ($matchingAccounts as $account) {
                
                // Assign the RSAccount to the Discord user
                $account->discord_user_id = $discordUser->id;
                $account->save();
            }
        }

        $this->info('Discord users and RSAccounts synced successfully.');
        return 0;
    }
}
