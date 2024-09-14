<?php

namespace Database\Factories;

use App\Models\NpcKill;
use App\Models\DiscordUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class NpcKillFactory extends Factory
{
    protected $model = NpcKill::class;
   

    public function definition()
    {
        return [
            'npc_id' => $this->faker->numberBetween(1, 100),  // Replace with your NPC ID range
            'ge_price' => $this->faker->numberBetween(1000, 100000),  // Example price range
            // Use an existing DiscordUser or create a new one if none exist
            'discord_user_id' => DiscordUser::query()->inRandomOrder()->value('id') ?? DiscordUser::factory(),
            'timestamp' => $this->faker->unixTime(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
}
