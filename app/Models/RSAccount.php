<?php

namespace App\Models;
use App\Models\PlayerMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RSAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'wom_id',
        'username',
        'display_name',
        'type',
        'build',
        'status',
        'country',
        'patron',
        'exp',
        'ehp',
        'ehb',
        'ttm',
        'tt200m',
        'registered_at',
        'wom_updated_at',
        'last_changed_at',
        'last_imported_at',
        'discord_user_id',
    ];

    protected $dates = [
        'registered_at',
        'wom_updated_at',
        'last_changed_at',
        'last_imported_at',
    ];

    public function discordUser()
    {
        return $this->belongsTo(DiscordUser::class);
    }

     // Define the relationship
     public function meta()
    {
        return $this->hasMany(PlayerMeta::class, 'r_s_accounts_id');
    }
 
     // Define a helper method to get meta by key
     public function getMeta($key)
     {
         return $this->meta()->where('key', $key)->first();
     }
    
}
