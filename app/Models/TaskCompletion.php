<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'discord_user_id', 'team_id'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(DiscordUser::class, 'discord_user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
