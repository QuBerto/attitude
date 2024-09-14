<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NpcKill;
use App\Models\NpcItem;
use App\Models\OsrsItem;
// app/Jobs/ProcessNpcKill.php
class ProcessNpcKill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $discordUser;
    protected $killdata;
    protected $timestamp;

    public function __construct($discordUser, $killdata, $timestamp)
    {
        $this->discordUser = $discordUser;
        $this->killdata = $killdata;
        $this->timestamp = $timestamp;
    }

    public function handle()
    {
        $npcKill = NpcKill::create([
            'npc_id' => $this->killdata['npcId'],
            'ge_price' => $this->killdata['gePrice'],
            'timestamp' => $this->timestamp,
            'discord_user_id' => $this->discordUser->id,
        ]);

        foreach ($this->killdata['items'] as $item) {
            $osrsItem = OsrsItem::where('item_id', $item['id'])->first();

            NpcItem::create([
                'npc_kill_id' => $npcKill->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);
        }
    }
}

