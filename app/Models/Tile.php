<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Tile extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = ['bingo_card_id', 'title'];

    public function bingoCard()
    {
        return $this->belongsTo(BingoCard::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function registerMediaConversions(?Media $media = null): void
{
    $this
        ->addMediaConversion('preview')
        ->fit(Fit::Contain, 300, 300)
        ->nonQueued();
}
}
