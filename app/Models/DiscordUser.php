<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordUser extends Model
{
    use HasFactory;

    protected $fillable = ['discord_id', 'username', 'nick', 'avatar', 'discriminator'];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user');
    }

    public function rsAccounts()
    {
        return $this->hasMany(RSAccount::class);
    }

    public function roles()
    {
        return $this->belongsToMany(DiscordRole::class, 'discord_user_role');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
