<?php

namespace App\Http\Controllers;

use App\Models\Drop;
use App\Models\RSAccount;
use App\Services\DropService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\DiscordUser;
class DropController extends Controller
{
    protected $dropService;
    protected $userAgent = 'AttitudeBot/1.0 (https://attitude.com;)';

    public function __construct(DropService $dropService)
    {
        $this->dropService = $dropService;
    }

    /**
     * Display a listing of the drops.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $drops = Drop::with('player')->get();
        return view('drops.index', compact('drops'));
    }

    /**
     * Display a listing of the drops.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
       
        $drops = Drop::with('player')->get();
        return view('drops.index', compact('drops'));
    }

    public function showByEventCode($eventcode)
    {
        // Retrieve the top 10 drops that match the given event code, sorted by gp in descending order
        $drops = Drop::with('player')
                    ->where('eventcode', $eventcode)
                    ->orderBy('gp', 'desc')
                    ->take(10)
                    ->get();
    
        // If no drops found, you may want to return a message or redirect
        if ($drops->isEmpty()) {
            return response()->json(['message' => 'No drops found for this event code.'], 404);
        }
    
        // Return the filtered drops in a view or as a JSON response
        return view('drops.index', compact('drops'));
    }
    

    /**
     * Store a newly created drop in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Drop request received', $request->all());

        $player = RSAccount::where('username', $request->input('username'))->first();

        if (!$player) {
            Log::error('Player not found: ' . $request->input('username'));
            return response()->json(['error' => 'Player not found'], 404);
        }

        $validated = $request->validate([
            'eventcode'  => 'required|string',
            'itemsource' => 'required|string',
            'items'      => 'required|array',
        ]);

        $totalValue = $this->calculateTotalItemValue($validated['items']);
        $drop = Drop::create([
            'player_id'  => $player->id,
            'eventcode'  => $validated['eventcode'],
            'itemsource' => $validated['itemsource'],
            'items'      => $validated['items'],
            'gp'         => $totalValue,
        ]);
        
        // Check if the current entry has the highest total value for the same event code
        $maxValueForEvent = Drop::where('eventcode', $validated['eventcode'])->max('gp');
        
        $isHighest = $totalValue >= $maxValueForEvent;
        
        // Prepare the response message
        $responseMessage = "#{$drop->id} {$player->username} got {$totalValue} GP";
        
        if ($isHighest) {
            $responseMessage .= " - New highest drop for event code {$validated['eventcode']}!";
        }
        
        return response()->json([
            'isMessageSet' => $isHighest,
            'message'      => $responseMessage,
        ]);
    }

    /**
     * Calculate the total value of the items.
     *
     * @param  array  $items
     * @return int
     */
    protected function calculateTotalItemValue(array $items)
    {
        $totalValue = 0;

        foreach ($items as $item) {
            $totalValue += $this->getItemValue($item['name']) * $item['quantity'];
        }

        return $totalValue;
    }

    /**
     * Get the value of an item from the OSRS Wiki API.
     *
     * @param  string  $itemName
     * @return int
     */
    protected function getItemValue($itemName)
    {
        if($itemName === "Coins"){
            return 1;
        }
        $cacheKey = 'item_value_' . strtolower($itemName);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($itemName) {
            $mappingResponse = Http::withHeaders(['User-Agent' => $this->userAgent])
                ->get('https://prices.runescape.wiki/api/v1/osrs/mapping');

            $items = $mappingResponse->json();

            $itemId = $this->getItemIdFromMapping($items, $itemName);

            if ($itemId === null) {
                Log::error("Item not found: $itemName");
                return 0;
            }

            $priceResponse = Http::withHeaders(['User-Agent' => $this->userAgent])
                ->get('https://prices.runescape.wiki/api/v1/osrs/latest', ['id' => $itemId]);

            $prices = $priceResponse->json();

            return $prices['data'][$itemId]['high'] ?? 0;
        });
    }

    /**
     * Get the item ID from the mapping data.
     *
     * @param  array   $items
     * @param  string  $itemName
     * @return int|null
     */
    protected function getItemIdFromMapping(array $items, $itemName)
    {
        foreach ($items as $item) {
            if (strtolower($item['name']) === strtolower($itemName)) {
                return $item['id'];
            }
        }

        return null;
    }
    public function npc_kill(Request $request)
    {
        $authorizationHeader = $request->header('Authorization'); // Retrieve token from request header
        $token = str_replace('Bearer: ', '', $authorizationHeader); // Now $token contains only 'xMo5KHqG9KfjgpwCW9BDcVdOR9GWPbbgO5sSkMfQ7vnWwKgCu810u

        $discordUser = DiscordUser::where('token', $token)->whereNotNull('token')->first();
       
        if (!$discordUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Token is valid, proceed with the request
        return response()->json(['message' => 'Success'], 200);
    }
    // public function npc_kill(Request $request)
    // {

    //     Log::info('Drop request received', $request->all());

    //     $player = RSAccount::where('username', $request->input('username'))->first();

    //     if (!$player) {
    //         Log::error('Player not found: ' . $request->input('username'));
    //         return response()->json(['error' => 'Player not found'], 404);
    //     }

    //     $validated = $request->validate([
    //         'eventcode'  => 'required|string',
    //         'itemsource' => 'required|string',
    //         'items'      => 'required|array',
    //     ]);

    //     $totalValue = $this->calculateTotalItemValue($validated['items']);
    //     $drop = Drop::create([
    //         'player_id'  => $player->id,
    //         'eventcode'  => $validated['eventcode'],
    //         'itemsource' => $validated['itemsource'],
    //         'items'      => $validated['items'],
    //         'gp'         => $totalValue,
    //     ]);

    //         // Log the raw JSON payload received in the request
    //         Log::info('Webhook request received', [
    //             'headers' => $request->headers->all(),  // Log the headers
    //             'body' => $request->getContent(),       // Log the raw body (JSON)
    //         ]);
    
    //         // Optionally, you can also log the parsed data as an array
    //         Log::info('Parsed request data', ['data' => $request->all()]);
    
    //         return response()->json(['status' => 'logged'], 200);
    // }
}
