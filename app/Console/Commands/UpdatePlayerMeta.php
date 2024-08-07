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

            $this->info("Syncing players on bingocard {$bingo->name}");
            foreach ($bingo->teams as $team) {
                foreach ($team->users as $user) {
                    $this->info("Syncing {$user->nick}");
                    foreach ($user->rsAccounts as $account) {
                        $this->info("Syncing {$account->username}");
                        if (!$account) {
                            $this->error("RSAccount with username '{$account->username}' not found.");
                            continue;
                        }

                        $this->updatePlayerMeta($account);
                        $this->info('Player meta data updated successfully.');
                    }
                }
            }
        } else {
            $acc = RSAccount::find($id);
            if (!$acc) {
                $this->error("RSAccount with ID '{$id}' not found.");
                return;
            }

            $this->updatePlayerMeta($acc);
            $this->info('Player meta data updated successfully.');
        }

        $this->info('Calling the capture:screenshot command...');
        Artisan::call('capture:screenshot', [
            'bingo' => $bingoCardId ?: 1 // Default to 1 if not provided
        ]);

        // Get the output of the capture:screenshot command
        $output = Artisan::output();
        $this->info('Screenshot command output:');
        $this->info($output);
    }

    protected function updatePlayerMeta(RSAccount $acc)
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
            
            if ($existingXp && ($existingXp == $xp)) {
                $this->info('Data didnt change, skipping');
                // Skip updating if the hash matches
                return;
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
