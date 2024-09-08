<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsrsItem extends Model
{
    use HasFactory;

    // Fillable fields
    protected $fillable = ['item_id', 'name', 'value', 'description', 'type', 'parent_id'];

    // Relationship to NpcItem, if an NpcItem can reference an OsrsItem
    public function npcItems()
    {
        return $this->hasMany(NpcItem::class, 'item_id', 'item_id');
    }

       // Define the relationship to the parent item
       public function parent()
       {
           return $this->belongsTo(OsrsItem::class, 'parent_id');
       }
   
       // Define the relationship to the child items
       public function children()
       {
           return $this->hasMany(OsrsItem::class, 'parent_id');
       }
}
