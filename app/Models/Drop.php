<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drop extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'eventcode',
        'itemsource',
        'items',
        'gp'
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function player()
    {
        return $this->belongsTo(RSAccount::class);
    }
}
