<?php

namespace App\Http\Controllers;

use App\Models\DiscordUser;
use App\Models\RSAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


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
    public function updateDiscordUser(Request $request)
    {
        $request->validate([
            'discord_user_id' => 'required|exists:discord_users,id',
        ]);

        $user = Auth::user();
        $discordUser = $user->discordUsers()->findOrFail($request->discord_user_id);

        // Here you can perform any additional logic needed for updating the selected Discord user

        return redirect()->route('profile.edit')->with('status', 'Discord user updated successfully!');
    }

    public function getToken(Request $request)
    {
        // Log the incoming request
        Log::info('Drop request received', [$request->all()]);
    
        // Validate the request data
        $request->validate([
            'discord_user_id' => 'required',
        ]);
    
        // Find the user by discord_id
        $user = DiscordUser::where('discord_id', $request->input('discord_user_id'))->first();
    
        // Check if the user exists
        if ($user) {
            // Return the token with a 200 OK status
            return response()->json(['token' => $user->token], 200);
        } else {
            // Return a 404 Not Found status with an error message
            return response()->json(['error' => 'Discord user not found'], 404);
        }
    }
    
}
