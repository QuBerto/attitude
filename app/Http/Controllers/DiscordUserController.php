<?php

namespace App\Http\Controllers;
use App\Models\DiscordRole;
use App\Models\DiscordUser;
use App\Models\RSAccount;
use App\Services\AttitudeDiscord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\WiseOldManService;
use PDO;

class DiscordUserController extends Controller
{
    public function index(Request $request)
    {
 
        // Get search term and role filter from the request
        $search = $request->input('search', '');
        $roleFilter = $request->input('role', '');

        // Fetch RS Accounts that are not linked with Discord users
        $rsAccounts = RSAccount::whereNull('discord_user_id')->get();

        // Get users with search and role filtering, paginated (default 50 per page)
        $users = DiscordUser::with('roles')
            ->when($search, function ($query, $search) {
                return $query->where('username', 'like', "%{$search}%")
                            ->orWhere('nick', 'like', "%{$search}%");
            })
            ->when($roleFilter, function ($query, $roleFilter) {
                return $query->whereHas('roles', function ($query) use ($roleFilter) {
                    $query->where('name', $roleFilter);
                });
            })
            ->paginate(25);

        // Get all roles for the filter dropdown
        $roles = DiscordRole::all();

        // Return the view with users, rsAccounts, and roles
        return view('discord.index', compact('users', 'rsAccounts', 'roles', 'search', 'roleFilter'));
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
    public function fixUserRoles()
    {

        // Fetch all users with their roles and RuneScape accounts
        $disc = new AttitudeDiscord(env('DISCORD_GUILD_ID'),env('DISCORD_BOT_TOKEN'));
        $users = DiscordUser::with(['roles', 'rsAccounts'])->get();
        $roles = DiscordRole::all();
        $response = [];
        $result = [];
        $message = '';
    
        // List of roles that are considered "steel or higher"
        $steelOrHigherRoles = ['steel', 'silver', 'gold', 'mithril', 'adamant', 'rune', 'dragon', 'moderator', 'owner', 'deputy owner'];
    
        // Define roles to remove (e.g., "bronze" and "iron")
        $rolesToRemove = ['bronze', 'iron'];
    
        foreach ($users as $user) {
            $discordRoles = [];
            $newRoles = $user->roles->pluck('role_id')->toArray(); // Get current roles as role IDs
    
            // Collect the user's roles from Discord
            foreach ($user->roles as $role) {
                $discordRoles[] = strtolower($role->name);
            }
    
            // Normalize roles for easier comparison
            if (in_array('deputy owner', $discordRoles) && !in_array('owner', $discordRoles)) {
                $discordRoles[] = 'owner';  // Treat 'deputy owner' as 'owner'
            }
    
            // Prepare user data
            $userData = [
                'username' => $user->username,
                'discord_roles' => $discordRoles,
                'accounts' => [],
                'has_silver_or_higher' => false,
                'is_verified' => false,
                'needs_role_update' => false, // Default is no role update needed
                'suggested_discord_role' => false, // Default no suggestion
                'suggested_osrs_role' => false // Default is no role update needed
            ];
    
            // If the user has connected RuneScape accounts
            if ($user->rsAccounts && count($user->rsAccounts) > 0) {
                foreach ($user->rsAccounts as $account) {
                    // Skip the "completionist" role check for RuneScape accounts
                    if ($account->role !== 'completionist') {
                        // Check if the RuneScape account role matches one of the user's Discord roles
                        $accountRoleMatches = in_array(strtolower($account->role), $discordRoles);
    
                        // Add account details and role matching to the response
                        $userData['accounts'][] = [
                            'account_username' => $account->username,
                            'account_role' => $account->role,
                            'role_matches' => $accountRoleMatches ? 'Correct' : 'Differs'
                        ];
                    }
    
                    // Determine if the user needs a "steel or higher" role
                    if (in_array(strtolower($account->role), $steelOrHigherRoles)) {
                        $userData['has_silver_or_higher'] = true;
                        // Check if the user's Discord roles include "steel or higher"
                        if (!array_intersect($steelOrHigherRoles, $discordRoles)) {
                            $userData['needs_role_update'] = true;
                            $userData['suggested_discord_role'] = 'steel'; // Suggest at least "steel"
                            // Add "steel" role to new roles
                            $newRoles[] = 1107697897744633906; // Steel role ID (replace with actual)
                        }
                    } else {
                        $userData['needs_role_update'] = true;
                        $userData['suggested_osrs_role'] = 'steel'; // Suggest at least "steel"
                        if (!array_intersect($steelOrHigherRoles, $discordRoles)) {
                            $userData['suggested_discord_role'] = 'steel'; // Suggest at least "steel"
                            $message .= '<@'.$user->discord_id.'> Needs role Steel \n';
                            $newRoles[] = 1107697897744633906; // Steel role ID (replace with actual)
                        }
                    }
                }
    
                // Ensure the user has the "verified" role if they have at least one RuneScape account
                if (in_array('verified', $discordRoles)) {
                    $userData['is_verified'] = true;
                } else {
                    $userData['is_verified'] = false;
                    $userData['needs_role_update'] = true;
                    $userData['suggested_discord_role'] = $userData['suggested_discord_role'] ?: 'verified';
                    // Add "verified" role to new roles
                    $message .= '<@'.$user->discord_id.'> Needs role Verified \n';
                    $newRoles[] = 1107697897744633905; // Verified role ID (replace with actual)
                }
    
                // Remove the "bronze" and "iron" roles if the user has them
                foreach ($rolesToRemove as $roleToRemove) {
                    if (in_array($roleToRemove, $discordRoles)) {
                        $roleIdToRemove = $roles->where('name', ucfirst($roleToRemove))->pluck('role_id')->first();
                        if (($key = array_search($roleIdToRemove, $newRoles)) !== false) {
                            unset($newRoles[$key]); // Remove role ID from new roles
                        }
                    }
                }
    
                // Determine the differences between current roles and new roles
                $rolesToAdd = array_diff($newRoles, $user->roles->pluck('role_id')->toArray());
                $rolesToRemove = array_diff($user->roles->pluck('role_id')->toArray(), $newRoles);
                if($rolesToAdd || $rolesToRemove){
                    // Prepare the result for this user
                    $result[] = [
                        'user_id' => $user->discord_id,
                        'role' => 1107697897744633906, // Role to assign (replace with actual role ID)
                        'roles' => $user->roles->pluck('role_id'), // Current roles
                        'new_roles' => array_values($newRoles), // Updated roles
                        'roles_to_add' => array_values($rolesToAdd), // Roles to be added
                        'roles_to_remove' => array_values($rolesToRemove), // Roles to be removed
                        'difference_text' => 'Adding roles: ' . implode(', ', $rolesToAdd) . '; Removing roles: ' . implode(', ', $rolesToRemove)
                    ];
                    //$disc->updateMemberRoles($user->discord_id, $newRoles);
                }
                
            }
    
            // Add user data to the response
            $response[] = $userData;
        }
    
        // Return the result as JSON
        return response()->json($message);
    }
    
}
