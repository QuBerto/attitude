<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AttitudeDiscord;

class DiscordServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AttitudeDiscord::class, function ($app) {
            $guildId = config('services.discord.guild_id');
            $token = config('services.discord.token');
            return new AttitudeDiscord($guildId, $token);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
