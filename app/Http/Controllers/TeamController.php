<?php

namespace App\Http\Controllers;

use App\Models\BingoCard;
use App\Models\DiscordUser;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'card' => 'required|string|max:255',
        ]);
        $bingoCard = BingoCard::find($request->input('card'));

        $team = Team::create($request->only('name'));
        $bingoCard->teams()->attach($team->id);

        return response()->json(['success' => true, 'team' => $team]);
    }

    public function addMember(Request $request, Team $team)
    {
        $request->validate([
            'discord_user_id' => 'required|exists:discord_users,id',
        ]);

        $team->users()->attach($request->input('discord_user_id'));

        return response()->json(['success' => true]);
    }
}
