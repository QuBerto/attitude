<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'tile_id', 'description'
    ];

    public function tile()
    {
        return $this->belongsTo(Tile::class);
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
}

