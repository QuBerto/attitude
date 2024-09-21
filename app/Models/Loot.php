<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loot extends Model
{
    use HasFactory;

    protected $fillable = ['source', 'category', 'value', 'kill_count', 'rs_account_id'];

    public function items()
    {
        return $this->hasMany(LootItem::class);
    }

    public function rsAccount()
    {
        return $this->belongsTo(RSAccount::class, 'rs_account_id');
    }
}
