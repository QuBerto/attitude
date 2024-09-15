<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $User = User::first();
        if($User){
            echo 'User already exist';
            return;
        }
        $name = env('USER_NAME', 'defaultuser');
        $email = env('USER_EMAIL', 'default@example.com');
        $password = env('USER_PASSWORD') ?: Str::random(10);

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]
        );

        // Log the password if it was randomly generated
        if (!env('USER_PASSWORD')) {
            $this->command->info("A user was created with the following credentials:");
            $this->command->info("Email: $email");
            $this->command->info("Password: $password");
        }
    }
}
