<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NpcKill;
use App\Models\NpcItem;
use App\Models\OsrsItem;
use App\Models\DiscordUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class NpcKillController extends Controller
{
    protected $userAgent = 'AttitudeBot/1.0 (https://attitude.com;)';
    // Store new NPC kill data

      /**
     * Display a listing of the drops.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $drops = NpcKill::with('items')->get(); // Eager load the items
        return view('npckill.index', compact('drops'));
    }
    public function store(Request $request)
    {
        $authorizationHeader = $request->header('Authorization'); // Retrieve token from request header
        $token = str_replace('Bearer: ', '', $authorizationHeader); // Now $token contains only '

        $discordUser = DiscordUser::where('token', $token)->whereNotNull('token')->first();
       
        
       
        if (!$discordUser) {
            Log::error('Discord user not found', $request->all());
            return response()->json(['error' => 'Unauthorized'], 401);
        }
       
        

        // Access the first element in the array (in this case the key is '0')
        $data = $request->input();
        $killdata = $data['data'];
        $timestamp = $data['timestamp'];
        Log::error('Creating npc kill', $request->all());
        $npcKill = NpcKill::create([
            'npc_id' => $killdata['npcId'],
            'ge_price' => $killdata['gePrice'],
            'timestamp' => $timestamp,
            'discord_user_id' => $discordUser->id,
        ]);

        // Now, $validatedData contains the validated array
        // Perform your logic for storing or processing the data
  
        foreach ($killdata['items'] as $item) {

            Log::error('Creating npc items', $request->all());
            $osrsItem = OsrsItem::where('item_id', $item['id'])->first();

            if ($osrsItem) {
                // Create an NpcItem record and associate it with the OsrsItem
                NpcItem::create([
                    'npc_kill_id' => $npcKill->id,
                    'item_id' => $item['id'], // This refers to the OSRS item
                    'quantity' => $item['quantity'],
                ]);
            } else {

                $itemId = $item['id'];
                if ($itemId === null) {
                   
                    continue;
                }
    
                $priceResponse = Http::withHeaders(['User-Agent' => $this->userAgent])
                    ->get('https://secure.runescape.com/m=itemdb_oldschool/api/catalogue/detail.json', ['item' => $itemId]);
                $prices = $priceResponse->json();
                if ($prices){
          
                    OsrsItem::create([
                        'item_id' => $itemId,
                        'name' => $prices['item']['name'],
                        'value' => $prices['item']['current']['price'] ?? 0,
                        'description' => $prices['item']['description'],
                    ]);
                    // Create an NpcItem record and associate it with the OsrsItem
                    NpcItem::create([
                        'npc_kill_id' => $npcKill->id,
                        'item_id' => $item['id'], // This refers to the OSRS item
                        'quantity' => $item['quantity'],
                    ]);
                }

            }
        }
        return response()->json(['message' => 'NpcKill and associated items processed successfully!'], 201);
    }

    // // Retrieve all NPC kills with associated items
    // public function index()
    // {
    //     $npcKills = NpcKill::with('items')->get(); // Eager load the items
    //     return response()->json($npcKills);
    // }

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
