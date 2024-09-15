<?php

namespace App\Models;
use App\Models\PlayerMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RSAccount extends Model
{
    use HasFactory;
    protected $table = "r_s_accounts";
    protected $fillable = [
        'wom_id',
        'username',
        'display_name',
        'type',
        'role',
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
     public function metas()
    {
        return $this->hasMany(PlayerMeta::class, 'r_s_accounts_id');
    }
 
    public function getMeta($key, $default = null)
    {   
  
        $meta = $this->metas()->where('key', $key)->first();
        return $meta ? $meta->value : $default;
    }

    public function updateMeta($key, $value)
    {
        $meta = $this->metas()->where('key', $key)->first();

        if ($meta) {
            $meta->update(['value' => $value]);
        } else {
            $this->metas()->create(['key' => $key, 'value' => $value]);
        }
    }
    
}
