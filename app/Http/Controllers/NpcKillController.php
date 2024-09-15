<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NpcKill;
use App\Models\NpcItem;
use App\Models\OsrsItem;
use App\Models\DiscordUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessNpcKill;


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
        // Fetch the drops, sort by 'created_at' from newest to oldest, and paginate the results
        $drops = NpcKill::with('items')
            ->orderBy('created_at', 'desc') // Sort by newest to oldest
            ->paginate(500); // Paginate with 15 items per page (you can adjust the number)

        return view('npckill.index', compact('drops'));
    }

    public function store(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        $token = str_replace('Bearer: ', '', $authorizationHeader);

        $discordUser = DiscordUser::where('token', $token)->whereNotNull('token')->first();

        if (!$discordUser) {
            Log::error('Discord user not found', [$token, $request->all()]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Access the data
        $data = $request->input();
        $killdata = $data['data'];
        $timestamp = $data['timestamp'];

        // Dispatch the job
        ProcessNpcKill::dispatch($discordUser, $killdata, $timestamp);

        return response()->json(['message' => 'NpcKill is being processed'], 202);
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
