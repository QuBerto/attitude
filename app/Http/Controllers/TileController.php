<?php

namespace App\Http\Controllers;

use App\Models\Tile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TileController extends Controller
{
    public function update(Request $request, Tile $tile)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        $tile->description = $request->input('title');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tiles', 'public');
            $tile->image = $path;
        }

        $tile->save();

        return response()->json(['success' => true]);
    }
}
