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
        // Log the incoming request
        Log::info('Player status request received', [$request->all()]);
        $authorizationHeader = $request->header('Authorization'); // Retrieve token from request header
        $token = str_replace('Bearer: ', '', $authorizationHeader); // Now $token contains only '

        $discordUser = DiscordUser::where('token', $token)->whereNotNull('token')->first();
       
        
       
        if (!$discordUser) {
            Log::error('Discord user not found', [$token, $request->all()]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::error('validating', [$token, $request->all()]);
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
        Log::error('Validated', [$token, $request->all()]);
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
        $threeMinutesAgo = Carbon::now()->subMinutes(3);

 
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
            // Format the usernames as a string suitable for Discord
            $usernames = $recentUpdates->pluck('user_name')->map(function($name) {
                return "<:Achiever:1282080229564616855> $name"; // Surround each username with backticks for Discord formatting
            })->implode('\n');

            // Return the formatted list
            return response()->json(['formatted_usernames' => $usernames], 200);
        }

        // Return the list of usernames in default format (JSON array)
        return response()->json($recentUpdates, 200);
    }
}