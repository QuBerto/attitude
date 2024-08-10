<?php

namespace App\Http\Controllers;

use App\Models\Tile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $tile
            ->addMedia($request->file('image'))
               ->toMediaCollection('tiles');
        }

        $tile->save();

        return response()->json(['success' => true]);
    }
}
