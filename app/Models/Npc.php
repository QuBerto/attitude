<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Npc extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'npc_id'];
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('npcs')
             ->useDisk('npcs'); // Use custom 'npcs' disk for media storage
    }
}
