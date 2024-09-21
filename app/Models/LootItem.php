<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LootItem extends Model
{
    use HasFactory;

    protected $fillable = ['loot_id', 'item_id', 'quantity', 'price_each', 'name'];

    public function loot()
    {
        return $this->belongsTo(Loot::class);
    }
     // Define the relationship to OsrsItem
     public function osrsItem()
     {
         return $this->belongsTo(OsrsItem::class, 'item_id', 'item_id');
     }
}
