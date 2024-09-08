<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpcItem extends Model
{
    use HasFactory;

    // Specify the fillable fields
    protected $fillable = ['npc_kill_id', 'item_id', 'quantity'];

    // Define the relationship with the NpcKill model
    public function npcKill()
    {
        return $this->belongsTo(NpcKill::class);
    }
}
