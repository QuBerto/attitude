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

    public function bingoCards()
    {
        return $this->belongsToMany(BingoCard::class, 'bingo_card_team');
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
}

