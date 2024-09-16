<?php

namespace App\Http\Controllers;
use App\Models\Emoji;
use App\Models\NpcKill;
use Illuminate\Http\Request;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;





class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function homepage()
    {
      
        return view('frontend.homepage');
    }

    /**
     * Display a listing of the resource.
     */


    public function kills()
    {
      
        // Get the emoji for golden spoon
        $spoon = Emoji::where('name', 'golden_spoon')->first();

        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Fetch the top 10 NPC kills by ge_price for the current month
        $kills = NpcKill::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->orderBy('ge_price', 'desc')
                    ->limit(10)
                    ->get();

        $totalLootPerUser = NpcKill::select('discord_user_id', DB::raw('SUM(ge_price) as total_loot'))
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->groupBy('discord_user_id')
                    ->orderBy('total_loot', 'desc')
                    ->distinct('discord_user_id')
                    ->limit(10)
                    ->get();
                
        // Return the view with both the kills and the total loot per user
        return view('frontend.test', compact('kills', 'spoon', 'totalLootPerUser'));
    }

}
