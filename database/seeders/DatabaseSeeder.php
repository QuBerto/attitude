<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscordUser;
use App\Models\NpcKill;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
   
        // Call the UserSeeder class
        $this->call(UserSeeder::class);
        //$this->call(BingoCardSeeder::class);
         // Check if any DiscordUser records exist
         $existingUsers = DiscordUser::count();

         if ($existingUsers > 0) {
             // If there are existing users, use them to create NpcKill records
             $this->assignKillsToExistingUsers();
         } else {
             // If no users exist, create new DiscordUsers and assign NpcKills to them
             $this->createNewUsersWithKills();
         }
    }
 
    /**
     * Create new users and assign NpcKills to them.
     */
    private function createNewUsersWithKills()
    {
        // Create 10 DiscordUsers and assign 5 NpcKills to each
        // DiscordUser::factory(10)->create()->each(function ($discordUser) {
        //     NpcKill::factory(5)->create(['discord_user_id' => $discordUser->id]);
        // });
    }

    /**
     * Assign NpcKills to existing users.
     */
    private function assignKillsToExistingUsers()
    {
        // Get all existing users
        $users = DiscordUser::all();

        // For each existing user, assign 5 NpcKills
        $users->each(function ($discordUser) {
            NpcKill::factory(5)->create(['discord_user_id' => $discordUser->id]);
        });
    }
}
