<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpcItem extends Model
{
    use HasFactory;

    protected $fillable = ['npc_kill_id', 'item_id', 'quantity'];

    // Define the relationship to OsrsItem
    public function osrsItem()
    {
        return $this->belongsTo(OsrsItem::class, 'item_id', 'item_id');
    }

    // Define the relationship to NpcKill
    public function npcKill()
    {
        return $this->belongsTo(NpcKill::class);
    }
}
