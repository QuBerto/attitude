<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(DiscordUser::class, 'team_user');
    }

    public function bingoCard()
    {
        return $this->belongsTo(BingoCard::class, 'bingo_card_team');
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
    public function hasCompletedAllTasks(Tile $tile)
    {
        $taskIds = $tile->tasks->pluck('id');
        $completedTaskIds = $this->completions->pluck('task_id');

        return $taskIds->diff($completedTaskIds)->isEmpty();
    }

    
    public function metas()
    {
        return $this->hasMany(TeamMeta::class);
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

