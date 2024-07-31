<?php

namespace App\Http\Controllers;

use App\Models\BingoCard;
use App\Models\DiscordUser;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Services\AttitudeDiscord;

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

    public function frontend(BingoCard $bingoCard)
    {
    
        return view('frontend.bingo.bingo', compact('bingoCard'));
    }
    public function frontend_team(BingoCard $bingoCard, Team $team)
    {
        return view('frontend.bingo.bingo', compact('bingoCard', 'team'));
    }
}
