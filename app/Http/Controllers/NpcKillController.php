<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NpcKill;
use App\Models\NpcItem;
use App\Models\DiscordUser;
use Illuminate\Support\Facades\Log;


class NpcKillController extends Controller
{
    // Store new NPC kill data
    public function store(Request $request)
    {
        $authorizationHeader = $request->header('Authorization'); // Retrieve token from request header
        $token = str_replace('Bearer: ', '', $authorizationHeader); // Now $token contains only 'xMo5KHqG9KfjgpwCW9BDcVdOR9GWPbbgO5sSkMfQ7vnWwKgCu810u

        $discordUser = DiscordUser::where('token', $token)->whereNotNull('token')->first();
        Log::info('Drop request received', [$request->all(), 'token' => $token, 'headers' => $request->headers] );
        
       
        if (!$discordUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info('Drop request received', [$request->all(), 'user' => $discordUser->username] );
        // Validate the incoming request data
        $data = $request->validate([
            'npcId' => 'required|integer',
            'gePrice' => 'required|integer',
            'timestamp' => 'required|integer',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
        ]);

        // Create a new NpcKill record
        $npcKill = NpcKill::create([
            'npc_id' => $data['npcId'],
            'ge_price' => $data['gePrice'],
            'timestamp' => $data['timestamp'],
        ]);

        // Create associated NpcItem records
        foreach ($data['items'] as $item) {
            NpcItem::create([
                'npc_kill_id' => $npcKill->id, // Foreign key to npc_kills
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json(['message' => 'NpcKill created successfully!'], 201);
    }

    // Retrieve all NPC kills with associated items
    public function index()
    {
        $npcKills = NpcKill::with('items')->get(); // Eager load the items
        return response()->json($npcKills);
    }

    // Retrieve a specific NPC kill by ID with associated items
    public function show($id)
    {
        $npcKill = NpcKill::with('items')->find($id);

        if (!$npcKill) {
            return response()->json(['error' => 'NpcKill not found'], 404);
        }

        return response()->json($npcKill);
    }
}
