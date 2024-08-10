<?php

namespace App\Http\Controllers;

use App\Models\Tile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class TileController extends Controller
{
    public function update(Request $request, Tile $tile)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            "bosses.$tile->id" => 'array',
            "bosses.$tile->id.*" => 'string'
        ]);
    
        $tile->title = $request->input('title');
        $tile->bosses = json_encode($validatedData['bosses'][$tile->id]);
        
        if ($request->hasFile('image')) {
            $tile->clearMediaCollection('tiles');
            $tile->addMedia($request->file('image'))->toMediaCollection('tiles');
        }
    
        $tile->save();
        
        // Cache key for the bingo card
        $cacheKey = 'bingo_card_' . $tile->bingoCard->id . '_bossList';
        
        // Fetch distinct bosses and cache the result for 24 hours
        $distinctBosses = DB::table('tiles')
            ->select(DB::raw('DISTINCT JSON_UNQUOTE(JSON_EXTRACT(bosses, "$[*]")) as boss'))
            ->whereNotNull('bosses')
            ->where('bingo_card_id', $tile->bingoCard->id)
            ->get();
    
        $bossList = collect($distinctBosses)->flatMap(function($item) {
            return json_decode($item->boss, true);
        })->unique();
    
        // Cache the boss list for 24 hours or override if it already exists
        Cache::put($cacheKey, $bossList, now()->addHours(24));
    
        return response()->json(['success' => true]);
    }
}
