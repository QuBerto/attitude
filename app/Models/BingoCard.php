<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoCard extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tiles()
    {
        return $this->hasMany(Tile::class);
    }

    
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'bingo_card_team');
    }

    
}
