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

        $tile->title = $request->input('title');

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
