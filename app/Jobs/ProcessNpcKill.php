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
        // Create a new NPC kill
        $npcKill = NpcKill::create([
            'npc_id' => $this->killdata['npcId'],
            'ge_price' => 0, // Initialize with 0, will update after item calculations
            'timestamp' => $this->timestamp,
            'discord_user_id' => $this->discordUser->id,
        ]);
    
        // Initialize the total value for this kill
        $totalValue = 0;
    
        // Loop through the items in the killdata
        foreach ($this->killdata['items'] as $item) {
            $osrsItem = OsrsItem::where('item_id', $item['id'])->first();
    
            if ($osrsItem) {
                // Calculate the value of this item (price * quantity)
                $itemValue = $osrsItem->value * $item['quantity'];
                $totalValue += $itemValue;
    
                // Debug output for logging (optional)
                //$this->info("OSRS: {$osrsItem->name} value: {$osrsItem->value} total for this item: {$itemValue}");
            }
    
            // Create a new NpcItem record associated with the NPC kill
            NpcItem::create([
                'npc_kill_id' => $npcKill->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);
        }
    
        // Update the NPC kill with the total value calculated from the items
        $npcKill->ge_price = $totalValue;
        $npcKill->save();
    
        // Output the final value for this kill (optional)
        //$this->info("Kill ID: {$npcKill->id} total GE price set to: {$totalValue}");
    }
}    

