<?php

namespace App\Http\Controllers;
use App\Models\Emoji;
use App\Models\NpcKill;
use Illuminate\Http\Request;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Log;




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
        
        // Start measuring time
        $startTime = microtime(true);
        $distinctUsers = NpcKill::whereYear('created_at', $currentYear)
        ->whereMonth('created_at', $currentMonth)
        ->distinct()
        ->pluck('discord_user_id');
        $kills = [];

        foreach ($distinctUsers as $userId) {
            $highestKill = NpcKill::where('discord_user_id', $userId)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->orderBy('ge_price', 'desc')  // Get the highest ge_price for this user
                ->first();  // Only get the top result (the highest one)

            if ($highestKill) {
                $kills[] = $highestKill;  // Add to the list of kills
            }
        }
        // Sort the kills array by ge_price in descending order
        usort($kills, function ($a, $b) {
            return $b->ge_price - $a->ge_price;
        });

        // Take the top 10 kills
        $kills = array_slice($kills, 0, 10);


        // Stop measuring time
        $endTime = microtime(true);

        // Calculate the total time taken
        $executionTime = $endTime - $startTime;



        $totalLootPerUser = NpcKill::select('discord_user_id', DB::raw('SUM(ge_price) as total_loot'))
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->groupBy('discord_user_id')
                    ->orderBy('total_loot', 'desc')
                    
                    ->limit(10)
                    ->get();
                
        // Return the view with both the kills and the total loot per user
        return view('frontend.test', compact('kills', 'spoon', 'totalLootPerUser'));
    }

}
