<?php

namespace App\Http\Controllers;
use App\Models\Emoji;
use App\Models\NpcKill;
use Illuminate\Http\Request;
use Carbon\Carbon; 


use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;



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
      
        //$this->sendImageToDiscord($channel, $outputPath);
        $spoon = Emoji::where('name', 'golden_spoon')->first();
    
        // Get the current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        // Fetch the top 10 NPC kills by ge_price for the current month
        $kills = NpcKill::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->orderBy('ge_price', 'desc')
                    ->limit(10)
                    ->get();
    

    
        return view('frontend.test', compact('kills', 'spoon'));
    }

}
