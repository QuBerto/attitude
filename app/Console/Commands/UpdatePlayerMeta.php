<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;
use App\Models\RSAccount;
use App\Models\BingoCard;
use App\Models\PlayerMeta;
use App\Services\WiseOldManService; // Ensure this is the correct namespace for your service
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;


use Illuminate\Support\Facades\DB;

class UpdatePlayerMeta extends Command
{
    protected $signature = 'sync:playermeta {id} {bingo_card_id?}';
    protected $description = 'Update player meta data for a given RSAccount id';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $id = $this->argument('id');
        $bingoCardId = $this->argument('bingo_card_id');

        if ($id == "all") {
            if (!$bingoCardId) {
                $this->error("Bingo Card ID is required when syncing all players.");
                return;
            }

            $bingo = BingoCard::find($bingoCardId);
            if (!$bingo) {
                $this->error("Bingo Card with ID '{$bingoCardId}' not found.");
                return;
            }

            // Generate the same cache key used when storing the boss list
            $cacheKey = 'bingo_card_' . $bingoCardId . '_bossList';

            // Attempt to retrieve the cached boss list
            $bossList = Cache::get($cacheKey);

            if (!$bossList) {
                // Fetch distinct bosses and cache the result for 24 hours
                $distinctBosses = DB::table('tiles')
                ->select(DB::raw('DISTINCT JSON_UNQUOTE(JSON_EXTRACT(bosses, "$[*]")) as boss'))
                ->whereNotNull('bosses')
                ->where('bingo_card_id', $bingoCardId)
                ->get();

                $bossList = collect($distinctBosses)->flatMap(function($item) {
                    return json_decode($item->boss, true);
                })->unique();
            } 
            $bossList = $bossList->toArray(); // Convert the Collection to an array
            $this->info("Syncing players on bingocard {$bingo->name}");
            $teamsData = [
                'bingo_id' => $bingo->id,
                'bingo_name' => $bingo->name,
            ];
            foreach ($bingo->teams as $team) {
                $teamData = [
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                    'data' => [], // Initialize the data array
                    'total_kills' => [] // To hold total kills for this team
                ];
                foreach ($team->users as $user) {
                    $this->info("Syncing {$user->nick}");
                    foreach ($user->rsAccounts as $account) {
                        $this->info("Syncing {$account->username}");
                        
                        if (!$account) {
                            $this->error("RSAccount with username '{$account->username}' not found.");
                            continue;
                        }
                        
                        $result = $this->updatePlayerMeta($account, $bossList);
                        if (is_array($result)){
                            $playerData = [
                                'account_id' => $account->id,
                                'username' => $account->username,
                                'data' => $result,
                            ];
                            $teamData['data'][] = $playerData;
                        }
                        
                        $this->info('Player meta data updated successfully.');
                    }
                }
                $teamsData[] = $teamData;
            }
            
            // Initialize arrays to store totals
            $teamKills = [];
            $totalKills = [];
            
            // Loop through each team in teamsData
            foreach ($teamsData as &$team) {
                // Skip if this is the bingo card metadata (bingo_id, bingo_name)
                if (!isset($team['team_id'])) {
                    continue;
                }
                
                $teamName = $team['team_name'];
            
                // Initialize the array for this team's kills
                $teamKills[$teamName] = [];
            
                // Loop through each player in the team
                foreach ($team['data'] as $player) {
                    // Loop through each boss in the player's data
                    foreach ($player['data'] as $boss => $kills) {
                        // Add to the team's total for this boss
                        if (!isset($teamKills[$teamName][$boss])) {
                            $teamKills[$teamName][$boss] = 0;
                        }
                        $teamKills[$teamName][$boss] += $kills;
            
                        // Add to the overall total for this boss
                        if (!isset($totalKills[$boss])) {
                            $totalKills[$boss] = 0;
                        }
                        $totalKills[$boss] += $kills;
                    }
                }
            
                // Add the team's aggregated boss kills back to the team's data
                $team['total_kills'] = $teamKills[$teamName];
            }
            
            // Optionally, add the total kills across all teams to the $teamsData array
            $teamsData['total_kills_all'] = $totalKills;
            // Generate a cache key with the bingo ID
            $cacheKey = "bingo_{$bingo->id}_teams_data";

            // Save the result in the cache for 24 hours
            Cache::put($cacheKey, $teamsData, now()->addHours(24));

            // Optionally, retrieve the cached data
            $cachedData = Cache::get($cacheKey);

            // Output the cached data for debugging
            $this->info("Cached Team Data:");
            $this->line(print_r($cachedData, true));
       

        } else {
            $acc = RSAccount::find($id);
            if (!$acc) {
                $this->error("RSAccount with ID '{$id}' not found.");
                return;
            }

            $this->updatePlayerMeta($acc);
            $this->info('Player meta data updated successfully.');
        }
        if (app()->environment('production')) {
            $this->info('Calling the capture:screenshot command...');
            Artisan::call('capture:screenshot', [
                'bingo' => $bingoCardId ?: 1 // Default to 1 if not provided
            ]);
        
            // Get the output of the capture:screenshot command
            $output = Artisan::output();
            $this->info('Screenshot command output:');
            $this->info($output);
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
            // Access the specific parts of the data
            $startsAt = $data->data->startsAt;
            $endsAt = $data->data->endsAt;
            $data = $data->data->data;
            // dd($data);
            // Serialize the data and generate a hash
            $serializedData = serialize($data);
            $dataHash = Hash::make($serializedData);
            $xp = data_get($data, 'skills.overall.experience.end', null);
            $xp = 1000000000000000000000;
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

            $acc->discordUser->teams[0]->updateMeta('last_update',Carbon::now()->toDateTimeString());
            $teamId = $acc->discordUser->teams[0]->id;
            // Define the cache key
            $cacheKey = "team_data_{$teamId}";
            $result = [];
            // Delete the cache key
            Cache::forget($cacheKey);
            // Store or update the meta data
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
                            $keyname = "{$metric}_{$key}_{$label}";
                            if (!$skip_saving){
                                PlayerMeta::updateOrCreate(
                                    ['r_s_accounts_id' => $acc->id, 'key' => $keyname],
                                    ['value' => $item]
                                );
                            }
                            
                            if ($label == 'gained' && $key == 'kills' && $bosslist && in_array($metric, $bosslist) && $item){
                                $result[$metric] =  $item;
                                $this->info("{$metric}  key: {$key}  label {$label} {$item}In biss list");
                            }
                        }
                        
                    }
                    
                }
                
            }
            return $result;
           
           
        } else {
            $this->error('Failed to retrieve data');
        }
    }
}
