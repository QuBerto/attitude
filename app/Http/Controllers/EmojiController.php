<?php

namespace App\Http\Controllers;

use App\Models\Emoji;
use Illuminate\Http\Request;

class EmojiController extends Controller
{
    // Display a listing of the emojis
    public function index()
    {
        $emojis = Emoji::all();
        return view('emojis.index', compact('emojis'));
    }

    // Show the form for creating a new emoji
    public function create()
    {
        return view('emojis.create');
    }

    // Store a newly created emoji in storage
    public function store(Request $request)
    {
        $request->validate([
            'emoji_id' => 'required|string',
            'name' => 'required|string',
        ]);

        Emoji::create($request->all());

        return redirect()->route('emojis.index')->with('success', 'Emoji created successfully.');
    }

    // Show the form for editing the specified emoji
    public function edit(Emoji $emoji)
    {
        return view('emojis.edit', compact('emoji'));
    }

    // Update the specified emoji in storage
    public function update(Request $request, Emoji $emoji)
    {
        $request->validate([
            'emoji_id' => 'required|string',
            'name' => 'required|string',
        ]);

        $emoji->update($request->all());

        return redirect()->route('emojis.index')->with('success', 'Emoji updated successfully.');
    }

    // Remove the specified emoji from storage
    public function destroy(Emoji $emoji)
    {
        $emoji->delete();

        return redirect()->route('emojis.index')->with('success', 'Emoji deleted successfully.');
    }
}
