<?php

namespace App\Http\Controllers;
use App\Models\Emoji;
use App\Models\NpcKill;
use Illuminate\Http\Request;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use App\Models\Loot;
use App\Models\LootItem;

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
        $distinctUsers = Loot::whereYear('created_at', $currentYear)
        ->whereMonth('created_at', $currentMonth)
        ->distinct()
        ->pluck('rs_account_id');
        $kills = [];

        foreach ($distinctUsers as $userId) {
            $highestKill = Loot::where('rs_account_id', $userId)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->orderBy('value', 'desc')  // Get the highest ge_price for this user
                ->first();  // Only get the top result (the highest one)

            if ($highestKill) {
                $kills[] = $highestKill;  // Add to the list of kills
            }
        }
        // Sort the kills array by ge_price in descending order
        usort($kills, function ($a, $b) {
            return $b->value - $a->value;
        });

        // Take the top 10 kills
        $kills = array_slice($kills, 0, 10);


        // Stop measuring time
        $endTime = microtime(true);

        // Calculate the total time taken
        $executionTime = $endTime - $startTime;



        $totalLootPerUser = Loot::select('rs_account_id', DB::raw('SUM(value) as total_loot'))
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->groupBy('rs_account_id')
                    ->orderBy('total_loot', 'desc')
                    
                    ->limit(10)
                    ->get();
                
        // Return the view with both the kills and the total loot per user
        return view('frontend.test', compact('kills', 'spoon', 'totalLootPerUser'));
    }

}
