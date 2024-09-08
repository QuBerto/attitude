<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpcKill extends Model
{
    use HasFactory;

    // Specify the fillable fields
    protected $fillable = ['npc_id', 'ge_price', 'timestamp'];

    // Define the relationship with the NpcItem model
    public function items()
    {
        return $this->hasMany(NpcItem::class);
    }
}
