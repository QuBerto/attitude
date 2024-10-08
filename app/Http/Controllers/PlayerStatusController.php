<?php

namespace App\Http\Controllers;

use Carbon\Carbon; // To handle date and time
use Illuminate\Http\Request;
use App\Models\PlayerStatus;
use App\Models\DiscordUser;
use Illuminate\Support\Facades\Log;
class PlayerStatusController extends Controller
{
    // Store or update player status for a Discord user
    public function store(Request $request)
    {
        $authorizationHeader = $request->header('Authorization'); // Retrieve token from request header
        $token = str_replace('Bearer: ', '', $authorizationHeader); // Now $token contains only '

        $discordUser = DiscordUser::where('token', $token)->whereNotNull('token')->first();
       
        
       
        if (!$discordUser) {
            Log::error('Discord user not found', [$token, $request->all()]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate the request data
        $data = $request->validate([
            'data.userName' => 'required|string',
            'data.accountType' => 'required|string',
            'data.combatLevel' => 'required|integer',
            'data.world' => 'required|integer',
            'data.worldPoint.x' => 'required|integer',
            'data.worldPoint.y' => 'required|integer',
            'data.worldPoint.plane' => 'required|integer',
            'data.maxHealth' => 'required|integer',
            'data.currentHealth' => 'required|integer',
            'data.maxPrayer' => 'required|integer',
            'data.currentPrayer' => 'required|integer',
            'data.currentRun' => 'required|integer',
            'data.currentWeight' => 'required|integer',
            'timestamp' => 'required|integer',
        ]);
        // Find or create a PlayerStatus record for this Discord user
        $playerStatus = PlayerStatus::updateOrCreate(
            ['user_name' => $data['data']['userName']], // Use discord_user_id to ensure only one record per user
            [
                'user_name' => $data['data']['userName'],
                'account_type' => $data['data']['accountType'],
                'combat_level' => $data['data']['combatLevel'],
                'world' => $data['data']['world'],
                'world_x' => $data['data']['worldPoint']['x'],
                'world_y' => $data['data']['worldPoint']['y'],
                'world_plane' => $data['data']['worldPoint']['plane'],
                'max_health' => $data['data']['maxHealth'],
                'current_health' => $data['data']['currentHealth'],
                'max_prayer' => $data['data']['maxPrayer'],
                'current_prayer' => $data['data']['currentPrayer'],
                'current_run' => $data['data']['currentRun'],
                'current_weight' => $data['data']['currentWeight'],
                'timestamp' => $data['timestamp'],
                'discord_user_id' => $discordUser->id,
            ]
        );

        return response()->json(['message' => 'Player status updated successfully'], 200);
    }
    // Method to get usernames updated in the last 3 minutes
    public function getRecentUpdates(Request $request)
    {
        // Calculate the time for 3 minutes ago
        $threeMinutesAgo = Carbon::now()->subHours(6);
    
        // Query the records, sort by discord_user_id and combat_level
        $recentUpdates = PlayerStatus::where('updated_at', '>=', $threeMinutesAgo)
            ->orderBy('discord_user_id', 'asc')  // Sort by discord_user_id in ascending order
            ->orderBy('combat_level', 'desc')    // Sort by combat_level in descending order
            ->get(['user_name', 'discord_user_id', 'combat_level']);
    
        // If no users were updated, return a message
        if ($recentUpdates->isEmpty()) {
            return response()->json(['formatted_usernames' => 'No players are online!'], 200);
        }
    
        // Check if the format query parameter is set to 'discord'
        if ($request->query('format') === 'discord') {
            // Track the previous discord_user_id to detect multiple accounts
            $previousDiscordUserId = null;
    
            // Format the usernames as a string suitable for Discord
            $usernames = $recentUpdates->map(function($player) use (&$previousDiscordUserId) {
                // Detect if this is the second or subsequent account of the same Discord user
                if ($player->discord_user_id === $previousDiscordUserId) {
                    // Indent or use a connected symbol for the second account
                    $formattedName = "<:Achiever:1282080229564616855> ➔ $player->user_name"; // Arrow or similar symbol
                } else {
                    // Normal formatting for the first account
                    $formattedName = "<:Achiever:1282080229564616855> $player->user_name";
                }
    
                // Update the previousDiscordUserId to the current one for the next iteration
                $previousDiscordUserId = $player->discord_user_id;
    
                return $formattedName;
            })->implode('\n');
    
            // Return the formatted list
            return response()->json(['formatted_usernames' => $usernames], 200);
        }
    
        // Return the list of usernames in default format (JSON array)
        return response()->json($recentUpdates, 200);
    }

    public function login($extra, $player, $user){
        Log::info('LOGIN:', [$player], true);
        $playerStatus = PlayerStatus::updateOrCreate(
            ['user_name' => $player->username], 
            [
                'user_name' => $player->username,
                'account_type' => 'normal',
                'combat_level' => 126,
                'world' => $extra['world'],
                'world_x' => 0,
                'world_y' => 0,
                'world_plane' => 1,
                'max_health' => 99,
                'current_health' => 99,
                'max_prayer' => 99,
                'current_prayer' => 99,
                'current_run' => 100,
                'current_weight' => 0,
                'timestamp' => time(),
                'discord_user_id' => $user->id,
            ]
        );
    }

    public function logout($player, $user)
    {
        Log::info('LOGIN:', [$player], true);
        // Attempt to find the player record by username and discord_user_id
        $playerStatus = PlayerStatus::where('user_name', $player->username)
            ->where('discord_user_id', $user->id)
            ->first();

        // If the player record exists, delete it
        if ($playerStatus) {
            $playerStatus->delete();
            Log::info("Player {$player} logged out and record deleted.");
        } else {
            Log::info("Player {$player} not found for logout.");
        }
    }
}