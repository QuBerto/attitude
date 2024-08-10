<?php

namespace App\Http\Controllers;

use App\Models\BingoCard;
use App\Models\DiscordUser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Services\AttitudeDiscord;
use App\Services\WiseOldManService;
class BingoCardController extends Controller
{
    public function index()
    {
        $bingoCards = BingoCard::all();
        return view('bingo-cards.index', compact('bingoCards'));
    }

    public function create()
    {
        return view('bingo-cards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $bingoCard = BingoCard::create($request->all());
        
        // Add tiles to the bingo card
        for ($i = 0; $i < 25; $i++) {
            $bingoCard->tiles()->create(['title' => '']);
        }

        return redirect()->route('bingo-cards.index')->with('status', 'Bingo Card created successfully.');
    }

    public function show(BingoCard $bingoCard)
    {
        // Fetch Discord users where 'nick' is not null
        $discordUsers = DiscordUser::whereNotNull('nick')->get();
        $discord = new AttitudeDiscord(env('DISCORD_GUILD_ID'),env('DISCORD_BOT_TOKEN'));
        $channels = $discord->listChannels();
        $wise = new WiseOldManService();
    
        // Return the view with the bingoCard and discordUsers
        return view('bingo-cards.show', compact('bingoCard', 'discordUsers', 'channels'));
    }
    

    public function edit(BingoCard $bingoCard)
    {
        return view('bingo-cards.edit', compact('bingoCard'));
    }

    public function update(Request $request, BingoCard $bingoCard)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $bingoCard->update($request->all());
        return redirect()->route('bingo-cards.index')->with('status', 'Bingo Card updated successfully.');
    }

    public function destroy(BingoCard $bingoCard)
    {
        $bingoCard->delete();
        return redirect()->route('bingo-cards.index')->with('status', 'Bingo Card deleted successfully.');
    }
    public function frontend_temp()
    {
        $bingoCard = BingoCard::first();
        return view('frontend.bingo.bingo', compact('bingoCard'));
    }
    public function frontend(BingoCard $bingoCard)
    {
    
        return view('frontend.bingo.bingo', compact('bingoCard'));
    }
    public function frontend_team(BingoCard $bingoCard, Team $team)
    {
        $teamData = $this->team_data($team);
        return view('frontend.bingo.bingo', compact('bingoCard', 'team', 'teamData'));
    }

    public function frontend_progress($bingocard, $team){
        $bingo = (Team::find($bingocard));
        $team = (Team::find($team));
        if (!$team || !$bingo){
            abort(404);
        }

        return view('frontend.bingo.teamstats', compact('bingo', 'team'));
        
    }
    public function team_data(Team $team){
        $teamId = $team->id;
         // Define a unique cache key for the team data
        $cacheKey = "team_data_{$team->id}";
       
        
        // Try to get the data from the cache
        $teamData = Cache::remember($cacheKey, 600, function () use ($teamId) {
            $team = Team::with('users.rsAccounts.metas')->findOrFail($teamId);

            // Initialize arrays to hold the data and totals
            $data = [];
            $totals = [];

            // Populate the data array with account data and calculate totals
            foreach ($team->users as $user) {
                foreach ($user->rsAccounts as $account) {
                    foreach ($account->metas as $meta) {
                        if ($meta->value != 0 && (strpos($meta->key, '_kills_gained') !== false || $meta->key == 'ehb_value_gained')) {
                            // Extract the key name and rename ehb_value_gained to EHB
                            if ($meta->key == 'ehb_value_gained') {
                                $keyName = 'EHB';
                            } else {
                                $keyName = ucfirst(str_replace('_', ' ', str_replace('_kills_gained', '', $meta->key)));
                            }

                            // Initialize the arrays if not already set
                            if (!isset($data[$keyName])) {
                                $data[$keyName] = [];
                            }
                            if (!isset($totals[$keyName])) {
                                $totals[$keyName] = 0;
                            }

                            // Add the value to the data and totals arrays
                            $data[$keyName][$account->username] = round($meta->value, 1);
                            $totals[$keyName] += $meta->value;
                        }
                    }
                }
            }

            // Get all account usernames
            $usernames = [];
            foreach ($team->users as $user) {
                foreach ($user->rsAccounts as $account) {
                    $usernames[] = $account->username;
                }
            }
            $usernames = array_unique($usernames);

            // Sort the keys alphabetically but put EHB first
            $sortedKeys = array_keys($data);
            usort($sortedKeys, function($a, $b) {
                if ($a == 'EHB') return -1;
                if ($b == 'EHB') return 1;
                return strcmp($a, $b);
            });

            // Return the data in an array
            return compact('data', 'totals', 'usernames', 'sortedKeys');
        });
        return $teamData;
    }
    
   
}
