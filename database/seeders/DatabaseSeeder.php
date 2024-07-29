<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Command to run before seeding
        shell_exec('php artisan sync:discord-users');
        shell_exec('php artisan sync:wiseoldman-users 5260');
        shell_exec('php artisan sync:discord-rsaccounts');
        // Call the UserSeeder class
        $this->call(UserSeeder::class);
        $this->call(BingoCardSeeder::class);

    }
}
