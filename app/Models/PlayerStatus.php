<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PlayerStatus extends Model
{
    use HasFactory;
    protected $table = 'player_status';
    protected $fillable = [
        'user_name', 'account_type', 'combat_level', 'world', 'world_x', 'world_y', 'world_plane', 
        'max_health', 'current_health', 'max_prayer', 'current_prayer', 'current_run', 
        'current_weight', 'timestamp', 'discord_user_id'
    ];

    // Define the relationship to DiscordUser
    public function discordUser()
    {
        return $this->belongsTo(DiscordUser::class, 'discord_user_id');
    }
   
}
