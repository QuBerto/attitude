<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NpcKill;
use App\Models\Loot;
use App\Models\LootItem;
class MigrateKills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-kills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $kills = NpcKill::all();

        foreach($kills as $kill){
            $this->handle_kill($kill);
        }
    }

    public function handle_kill($kill){
        $this->info("Proces ID: {$kill->id}");
        if (!$kill->npc){
            $kill->delete();
            $this->warn("Skipping NPC not found for ID: {$kill->id}");
            return;
        }
        $source = $kill->npc->name;
        $value = $kill->ge_price;
        $user = $kill->discordUser;
        if (!isset($user->rsAccounts[0])){
            $kill->delete();
            $this->warn("Skipping no rs acount found for ID: {$user->username}");
            return;
        }
        $rsAccountId = $user->rsAccounts[0]->id;
      

        $loot = Loot::create([
            'source' => $source,
            'category' => 'NPC',
            'kill_count' => 0,
            'value' => $value,
            'rs_account_id' => $rsAccountId, // Associate the account if found
            'updated_at' => $kill->updated_at,
            'created_at' =>$kill->created_at
        ]);
        $total = 0;
        $this->info("Loot created with ID: {$loot->id}");
 
        // Save the loot items
        foreach ($kill->items as $item) {
            $name = $item['item_id'];
            $value = 0;
            if ($item->osrsItem){
                $value = $item->osrsItem->value;
                $name = $item->osrsItem->name;
            }

            $lootItem = LootItem::create([
                'loot_id' => $loot->id,
                'item_id' => $item->item_id,
                'quantity' => $item->quantity,
                'price_each' => $value,
                'name' => $name,
                'updated_at' => $item->updated_at,
                'created_at' =>$item->created_at
            ]);
            $this->info("Loot created with ID: {$lootItem->id} {$name} {$value}");
            $item->delete();
        }
        $kill->delete();
    }
}
