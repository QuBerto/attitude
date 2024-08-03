<?php

namespace App\Http\Controllers;

use App\Models\DiscordUser;
use App\Models\RSAccount;
use Illuminate\Http\Request;

class DiscordUserController extends Controller
{
    public function index()
    {
        $rsAccounts = RSAccount::whereNull('discord_user_id')->get();
        $users = DiscordUser::with('roles')->get();
        return view('discord-users.index', compact('users', 'rsAccounts'));
    }

    public function show(DiscordUser $discordUser)
    {
        $rsAccounts = RSAccount::whereNull('discord_user_id')->get();
        return view('discord-users.show', compact('discordUser', 'rsAccounts'));
    }

    public function assignPlayer(Request $request, DiscordUser $discordUser)
    {
        $request->validate([
            'rs_account_id' => 'required|exists:r_s_accounts,id',
        ]);

        $account = RSAccount::findOrFail($request->rs_account_id);
        $account->discord_user_id = $discordUser->id;
        $account->save();

        return redirect()->route('discord-users.show', $discordUser->id)->with('status', 'Player assigned successfully.');
    }

    public function unassignPlayer(DiscordUser $discordUser, RSAccount $account)
    {
        if ($account->discord_user_id !== $discordUser->id) {
            return redirect()->route('discord-users.show', $discordUser->id)->with('error', 'Player is not assigned to this Discord user.');
        }

        $account->discord_user_id = null;
        $account->save();

        return redirect()->route('discord-users.show', $discordUser->id)->with('status', 'Player unassigned successfully.');
    }

    public function unconnected(){
        $rsAccounts = RSAccount::whereNull('discord_user_id')->get();
        $users = DiscordUser::whereDoesntHave('rsAccounts')->get();
     
        return view('discord-users.unconnected', compact('users', 'rsAccounts'));
    }
}
