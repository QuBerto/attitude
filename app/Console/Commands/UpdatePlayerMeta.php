<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RSAccount;
use Carbon\Carbon;

class UpdatePlayerMeta extends Command
{
    // Add an optional argument for the maximum number of accounts to update
    protected $signature = 'sync:playermeta {maxAccounts?}';
    protected $description = 'Update player meta data for RSAccount records';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the maxAccounts argument or default to 10 if not provided
        $maxAccounts = $this->argument('maxAccounts') ?? 10;

        // Get the timestamp for six hours ago
        $sixHoursAgo = Carbon::now()->subHours(6);

        // Query all accounts that haven't been updated in the last 6 hours
        $accountsNeedingUpdate = RSAccount::where('wom_updated_at', '<', $sixHoursAgo)
                                          ->orWhereNull('wom_updated_at')
                                          ->count();

        // Log how many accounts need to be updated
        $this->info("There are {$accountsNeedingUpdate} accounts that need to be updated.");

        // Query and limit the number of accounts to be updated based on the maxAccounts argument
        $accounts = RSAccount::where('wom_updated_at', '<', $sixHoursAgo)
                            ->orWhereNull('wom_updated_at')  // Also include accounts where wom_updated_at is null
                            ->limit($maxAccounts)
                            ->get();

        // Loop through the accounts and update their meta data
        foreach ($accounts as $account) {
            // Log message before sending the update request
            $this->info('Updating player meta data for ' . $account->username);
            
            // Send the update request (implement sendUpdateRequest in your service or class)
            $this->sendUpdateRequest($account);

            // Update the wom_updated_at field to the current timestamp after the update
            $account->update(['wom_updated_at' => Carbon::now()]);

            // Log success message
            $this->info('Player meta data updated successfully for ' . $account->username);
        }

        // Log when no accounts need to be updated
        if ($accounts->isEmpty()) {
            $this->info('No accounts were updated.');
        }
    }
    protected function updatePlayerMeta(RSAccount $acc, $bosslist = [])
    {
       
        $wise = new WiseOldManService();
        $response = $wise->getPlayerGain($acc->username);

        // Assuming $response is already an array
        $data = $response->getData();
   
        // Check if the response was successful
        if ($data->success) {
            
         
            $endsAt = $data->data->endsAt;
            $data = $data->data->data;
            // dd($data);
            // Serialize the data and generate a hash
            $serializedData = serialize($data);
            $dataHash = Hash::make($serializedData);
            $xp = data_get($data, 'skills.overall.experience.end', null);
    
            // Use $xp as needed
            if ($xp !== null) {
                
            } else {
                $this->info('Experience end date is not available.');
                return;
            }
            // Check if the hash has changed
            $existingXp = PlayerMeta::where('r_s_accounts_id', $acc->id)
                ->where('key', 'overall_experience_end')
                ->value('value');
            $skip_saving = false;
            if ($existingXp && ($existingXp == $xp)) {
                $this->info('Data didnt change, skipping');
                // Skip updating if the hash matches
                $skip_saving = true;
            }

            // Update the hash in PlayerMeta
            PlayerMeta::updateOrCreate(
                ['r_s_accounts_id' => $acc->id, 'key' => 'data_hash'],
                ['value' => $dataHash]
            );

            // Update the last updated time
            PlayerMeta::updateOrCreate(
                ['r_s_accounts_id' => $acc->id, 'key' => 'last_update'],
                ['value' => Carbon::now()->toDateTimeString()]
            );

            $result = [];
 
            $dataToInsert = []; // Accumulate data here for batch insert/update

            foreach ($data as $category => $details) {
                foreach ($details as $metric => $detail) {
                    foreach ($detail as $key => $values) {
                        if ($key == 'metric') {
                            continue;
                        }
            
                        foreach ($values as $label => $item) {
                            if ($item == "-1") {
                                $item = 0;
                            }
            
                            $keyname = "gained_{$metric}_{$key}_{$label}";
            
                            // Prepare data for batch upsert
                            $dataToInsert[] = [
                                'r_s_accounts_id' => $acc->id,
                                'key' => $keyname,
                                'value' => $item,
                            ];
                        }
                    }
                }
            }
            
            // Perform batch insert/update (upsert) if there is data to insert
            if (!empty($dataToInsert)) {
                PlayerMeta::upsert($dataToInsert, ['r_s_accounts_id', 'key'], ['value']);
            }
            
            return $result;
        }
    }

    public function sendUpdateRequest($acc){
       
        $wise = new WiseOldManService();
        $data = $wise->updatePlayer($acc->username);
        if (!$data['success']){
            $this->info('Failed to ask for update.');
            return;
        }
        

        $dataToInsert = []; // Accumulate data here for processing
        foreach ($data['data']['latestSnapshot']['data'] as $category => $details) {
            
            foreach ($details as $metric => $detail) {
                
                foreach ($detail as $key => $value) {
                   
                    if ($key == 'metric') {
                        continue;
                    }
                   
                    $keyname = "{$metric}_{$key}";
            
                    // Prepare data for update or create
                    $dataToInsert[] = [
                        'r_s_accounts_id' => $acc->id,
                        'key' => $keyname,
                        'value' => $value,
                    ];
                }
            }
        }
        
        // Perform updateOrCreate operation if there is data to insert
        foreach ($dataToInsert as $data) {
            PlayerMeta::updateOrCreate(
                [
                    'r_s_accounts_id' => $data['r_s_accounts_id'], 
                    'key' => $data['key']
                ],
                [
                    'value' => $data['value']
                ]
            );
        }
        
    }
}




























 // $id = $this->argument('id');
        // $bingoCardId = $this->argument('bingo_card_id');

        // if ($id == "all") {
        //     if (!$bingoCardId) {
        //         $this->error("Bingo Card ID is required when syncing all players.");
        //         return;
        //     }

        //     $bingo = BingoCard::find($bingoCardId);
        //     if (!$bingo) {
        //         $this->error("Bingo Card with ID '{$bingoCardId}' not found.");
        //         return;
        //     }

        //     // Generate the same cache key used when storing the boss list
        //     $cacheKey = 'bingo_card_' . $bingoCardId . '_bossList';

        //     // Attempt to retrieve the cached boss list
        //     $bossList = Cache::get($cacheKey);

        //     if (!$bossList) {
        //         // Fetch distinct bosses and cache the result for 24 hours
        //         $distinctBosses = DB::table('tiles')
        //         ->select(DB::raw('DISTINCT JSON_UNQUOTE(JSON_EXTRACT(bosses, "$[*]")) as boss'))
        //         ->whereNotNull('bosses')
        //         ->where('bingo_card_id', $bingoCardId)
        //         ->get();

        //         $bossList = collect($distinctBosses)->flatMap(function($item) {
        //             return json_decode($item->boss, true);
        //         })->unique();
        //     } 
        //     $bossList = $bossList->toArray(); // Convert the Collection to an array
        //     $this->info("Syncing players on bingocard {$bingo->name}");
        //     $teamsData = [
        //         'bingo_id' => $bingo->id,
        //         'bingo_name' => $bingo->name,
        //     ];
        //     foreach ($bingo->teams as $team) {
        //         $teamData = [
        //             'team_id' => $team->id,
        //             'team_name' => $team->name,
        //             'data' => [], // Initialize the data array
        //             'total_kills' => [] // To hold total kills for this team
        //         ];
        //         foreach ($team->users as $user) {
        //             $this->info("Syncing {$user->nick}");
        //             foreach ($user->rsAccounts as $account) {
        //                 $this->info("Syncing {$account->username}");
                        
        //                 if (!$account) {
        //                     $this->error("RSAccount with username '{$account->username}' not found.");
        //                     continue;
        //                 }
                        
        //                 $result = $this->updatePlayerMeta($account, $bossList);
        //                 if (is_array($result)){
        //                     $playerData = [
        //                         'account_id' => $account->id,
        //                         'username' => $account->username,
        //                         'data' => $result,
        //                     ];
        //                     $teamData['data'][] = $playerData;
        //                 }
                        
        //                 $this->info('Player meta data updated successfully.');
        //             }
        //         }
        //         $teamsData[] = $teamData;
        //     }
            
        //     // Initialize arrays to store totals
        //     $teamKills = [];
        //     $totalKills = [];
            
        //     // Loop through each team in teamsData
        //     foreach ($teamsData as &$team) {
        //         // Skip if this is the bingo card metadata (bingo_id, bingo_name)
        //         if (!isset($team['team_id'])) {
        //             continue;
        //         }
                
        //         $teamName = $team['team_name'];
            
        //         // Initialize the array for this team's kills
        //         $teamKills[$teamName] = [];
            
        //         // Loop through each player in the team
        //         foreach ($team['data'] as $player) {
        //             // Loop through each boss in the player's data
        //             foreach ($player['data'] as $boss => $kills) {
        //                 // Add to the team's total for this boss
        //                 if (!isset($teamKills[$teamName][$boss])) {
        //                     $teamKills[$teamName][$boss] = 0;
        //                 }
        //                 $teamKills[$teamName][$boss] += $kills;
            
        //                 // Add to the overall total for this boss
        //                 if (!isset($totalKills[$boss])) {
        //                     $totalKills[$boss] = 0;
        //                 }
        //                 $totalKills[$boss] += $kills;
        //             }
        //         }
            
        //         // Add the team's aggregated boss kills back to the team's data
        //         $team['total_kills'] = $teamKills[$teamName];
        //     }
            
        //     // Optionally, add the total kills across all teams to the $teamsData array
        //     $teamsData['total_kills_all'] = $totalKills;
        //     // Generate a cache key with the bingo ID
        //     $cacheKey = "bingo_{$bingo->id}_teams_data";

        //     // Save the result in the cache for 24 hours
        //     Cache::put($cacheKey, $teamsData, now()->addHours(24));

        //     // Optionally, retrieve the cached data
        //     $cachedData = Cache::get($cacheKey);

        //     // Output the cached data for debugging
        //     $this->info("Cached Team Data:");
        //     $this->line(print_r($cachedData, true));
       

        // } else {
        //     $acc = RSAccount::find($id);
        //     if (!$acc) {
        //         $this->error("RSAccount with ID '{$id}' not found.");
        //         return;
        //     }

        //     $this->updatePlayerMeta($acc);
        //     $this->info('Player meta data updated successfully.');
        // }
        // if (app()->environment('production')) {
        //     $this->info('Calling the capture:screenshot command...');
        //     Artisan::call('capture:screenshot', [
        //         'bingo' => $bingoCardId ?: 1 // Default to 1 if not provided
        //     ]);
        
        //     // Get the output of the capture:screenshot command
        //     $output = Artisan::output();
        //     $this->info('Screenshot command output:');
        //     $this->info($output);
        // }