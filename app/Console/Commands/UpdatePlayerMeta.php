<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RSAccount;
use App\Models\BingoCard;
use App\Models\PlayerMeta;
use App\Services\WiseOldManService; // Ensure this is the correct namespace for your service

class UpdatePlayerMeta extends Command
{
    protected $signature = 'sync:playermeta {id}';
    protected $description = 'Update player meta data for a given RSAccount id';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $id = $this->argument('id');
        if ($id == "all"){
            $bingo = BingoCard::find(1);
            $this->info("Syncing players on bingocard {$bingo->name}");
            foreach($bingo->teams as $team){
                $this->info("Syncing {$team->name}");
                foreach($team->users as $user){
                    $this->info("Syncing {$user->nick}");
                    foreach($user->rsAccounts as $account){
                        $this->info("Syncing {$account->username}");
                        if (!$account) {
                            $this->error("RSAccount with username '{$id}' not found.");
                            return;
                        }
                
                
                        $this->updatePlayerMeta($account);
                        $this->info('Player meta data updated successfully.');
                    }
                    
                }
            }
           
        }
        else{
            $acc = RSAccount::find($id)->first();
            if (!$acc) {
                $this->error("RSAccount with username '{$id}' not found.");
                return;
            }
    
    
            $this->updatePlayerMeta($acc);
            $this->info('Player meta data updated successfully.');
        }
        
       
    }

    protected function updatePlayerMeta(RSAccount $acc)
    {
        
        $wise = new WiseOldManService();
        $data = $wise->getPlayerGain($acc->username);

        // Assuming $response is already an array
        $data = $data->getData();
        
        // Check if the response was successful
        if ($data->success) {
           
            // Access the specific parts of the data
            $startsAt = $data->data->startsAt;
            $endsAt = $data->data->endsAt;
            $data = $data->data->data;
        
            $skills = $data->skills;
            $bosses = $data->bosses;
            $activities = $data->activities;
            $computed = $data->computed;

            foreach ($data as $category => $details) {
                foreach ($details as $metric => $detail) {
                    
                    foreach ($detail as $key => $values) {
                        if ($key == 'metric'){
                            continue;
                        };
                      
                        foreach ($values as $label => $item) {
                            if ($item == "-1") {
                                $item = 0;
                            }
                            $keyname = "{$metric}_{$key}_{$label}";
                            // Store or update the meta data
                            PlayerMeta::updateOrCreate(
                                ['r_s_accounts_id' => $acc->id, 'key' => $keyname],
                                ['value' => $item]
                            );
                        }
                    }
                }
            }
        } else {
            $this->error('Failed to retrieve data');
        }
    }
}
