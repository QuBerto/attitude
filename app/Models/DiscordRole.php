<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'name',
        'description',
        'permissions',
        'permissions_new',
        'position',
        'color',
        'hoist',
        'managed',
        'mentionable',
        'icon',
        'unicode_emoji',
        'flags',
    ];
    public function users()
    {
        return $this->belongsToMany(DiscordUser::class, 'discord_user_role');
    }
}
