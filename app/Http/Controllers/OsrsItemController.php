<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OsrsItem;

class OsrsItemController extends Controller
{
    // Display all OSRS items
    public function index()
    {
        $items = OsrsItem::all();
        return view('osrs-items.index', ['items' => $items]);
    }

    // Display the form for creating a new item
    public function create()
    {
        return view('osrs-items.create');
    }

    // Store a new OSRS item
    public function store(Request $request)
    {
        // Validate the request
        $data = $request->validate([
            'item_id' => 'required|integer|unique:osrs_items,item_id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        // Create the item
        OsrsItem::create($data);

        return redirect()->route('osrs-items.index')->with('success', 'OSRS Item created successfully!');
    }

    // Display the form for editing an existing item
    public function edit($item_id)
    {
        $item = OsrsItem::where('item_id', $item_id)->firstOrFail();
        return view('osrs-items.edit', ['item' => $item]);
    }

    // Update an existing OSRS item
    public function update(Request $request, $item_id)
    {
        // Validate the request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        // Find and update the item
        $item = OsrsItem::where('item_id', $item_id)->firstOrFail();
        $item->update($data);

        return redirect()->route('osrs-items.index')->with('success', 'OSRS Item updated successfully!');
    }

    // Delete an OSRS item
    public function destroy($item_id)
    {
        $item = OsrsItem::where('item_id', $item_id)->firstOrFail();
        $item->delete();

        return redirect()->route('osrs-items.index')->with('success', 'OSRS Item deleted successfully!');
    }
}
