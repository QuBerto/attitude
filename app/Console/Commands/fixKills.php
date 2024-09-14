<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NpcKill;
class fixKills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-kills';

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
            $value = 0;
            
            foreach($kill->items as $item){
                if ($item->osrsItem){
                    if ($item->osrsItem->value > 0){
                        $value += $item->osrsItem->value;
                    }
                }
            }
            $this->info("Kill ID: {$kill->id} value set to: {$value}");
            $kill->ge_price = $value;
            $kill->save();
        }
    }
}
