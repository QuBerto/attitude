<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerMeta extends Model
{
    use HasFactory;
    protected $table = 'player_meta';
    protected $fillable = [
        'r_s_accounts_id', 'key', 'value'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public function player()
    {
        return $this->belongsTo(RSAccount::class);
    }
     /**
     * Scope a query to only include meta records with keys that match a given pattern.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $pattern
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKeyLike($query, $pattern)
    {
        return $query->where('key', 'LIKE', $pattern);
    }
}
