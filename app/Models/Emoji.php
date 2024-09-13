<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Emoji extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['emoji_id', 'name'];
    protected $table = 'emojis';
    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->useDisk('public') // Use the 'public' disk to store files in 'storage/app/public'
             ->singleFile(); // Limit to one image per emoji
    }
}

 
