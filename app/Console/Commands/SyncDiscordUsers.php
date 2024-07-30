<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AttitudeDiscord;
use App\Models\DiscordUser;
use App\Models\DiscordRole;

class SyncDiscordUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:discord-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Discord users with the application database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AttitudeDiscord $discord)
    {
        $discordMembers = $discord->connect('members?limit=1000');
        $roles = $discord->listRoles();
   
        foreach ($roles as $roleData) {
            DiscordRole::updateOrCreate(
                ['role_id' => $roleData['id']],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'],
                    'permissions' => $roleData['permissions'],
                    'permissions_new' => $roleData['permissions_new'],
                    'position' => $roleData['position'],
                    'color' => $roleData['color'],
                    'hoist' => $roleData['hoist'],
                    'managed' => $roleData['managed'],
                    'mentionable' => $roleData['mentionable'],
                    'icon' => $roleData['icon'],
                    'unicode_emoji' => $roleData['unicode_emoji'],
                    'flags' => $roleData['flags'],
                ]
            );
            $this->info('Synced user '. $roleData['name']);
        }
        foreach ($discordMembers as $member) {
            // Sync user
            $user = DiscordUser::updateOrCreate(
                ['discord_id' => $member['user']['id']],
                [
                    'username' => $member['user']['username'],
                    'nick' => $member['nick'] ?? null,
                    'avatar' => $member['user']['avatar'],
                    'discriminator' => $member['user']['discriminator'],
                ]
            );
            $this->info('Synced user '. $member['user']['username']);
            

            // Sync roles
            $roleIds = [];
            foreach ($member['roles'] as $roleId) {
                $role = DiscordRole::where('role_id', $roleId)->first( );
                if ($role){
                    $roleIds[] = $role->id;
                }
                else{
                    $this->error('Cant find .'.$roleId);
                }
                
            }
            $user->roles()->sync($roleIds);
        }

        $this->info('Discord users synchronized successfully.');
        return 0;
    }
}