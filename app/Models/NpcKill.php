<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpcKill extends Model
{
    use HasFactory;

    protected $fillable = ['npc_id', 'ge_price', 'timestamp', 'discord_user_id'];

    // Define the relationship to DiscordUser
    public function discordUser()
    {
        return $this->belongsTo(DiscordUser::class, 'discord_user_id');
    }

    public function items()
    {
        return $this->hasMany(NpcItem::class);
    }
}
