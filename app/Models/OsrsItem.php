<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class OsrsItem extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    // Fillable fields
    protected $fillable = ['item_id', 'name', 'slug', 'value', 'description', 'type', 'parent_id'];


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
       protected static function boot()
       {
           parent::boot();
   
           static::saving(function ($item) {
               if (empty($item->slug)) {
                   $item->slug = Str::slug($item->name);
               }
           });
       }

       public function registerMediaCollections(): void
       {
           
            $this->addMediaCollection('default')
            ->useDisk('osrs-items'); // Use the custom 'media' disk for media storage
       }
   
       /**
        * Customize the directory where media files are stored.
        */
       public function registerMediaConversions(Media $media = null): void
       {
           // Define your media conversions here (e.g. thumbnails)
       }
   
       /**
        * Override the method to set a custom directory for storing media.
        */
       public function getMediaDirectory(): string
       {
           return 'osrs-items'; // This is the custom directory
       }
}
