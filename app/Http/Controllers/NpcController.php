<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Npc;
use App\Enums\NpcIds;
use App\Models\OsrsItem;

class NpcController extends Controller
{
    public function index(Request $request)
    {
        // Default values
        $perPage = $request->input('per_page', 50); // Default 10 items per page
        $search = $request->input('search', '');    // Search term (if any)
        $sortBy = $request->input('sort_by', 'npc_id'); // Default sort by 'item_id'
        $sortOrder = $request->input('sort_order', 'asc'); // Default ascending order

        // Query the OSRS items with filters and sorting
        $npcs = Npc::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        return view('npcs.index', [
            'npcs' => $npcs,
            'perPage' => $perPage,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    // Display the form for creating a new item
    public function create()
    {
        return view('npcs.create');
    }



    // Store a new OSRS item
    public function store(Request $request) {
        
    }

    // Display the form for editing an existing item
    public function edit($npc_id)
    {
        $items = OsrsItem::all();
        $npc = Npc::where('npc_id', $npc_id)->firstOrFail();
        return view('npcs.edit', ['npc' => $npc]);
    }

    // Update an existing OSRS item
    public function update(Request $request, $npc_id)
    {
        $npc = Npc::where('npc_id', $npc_id)->first();
        $npc->name = $request->input('name');
        
        if ($request->hasFile('image')) {
          
            $npc->clearMediaCollection('npcs');  // Remove old image
            $npc->addMediaFromRequest('image')->toMediaCollection('npcs');  // Add new image
        }

        $npc->save();

        // Redirect back to the same edit page with a success message
        return redirect()->route('npcs.edit', $npc_id)->with('success', 'NPC updated successfully.');
    }

    public function destroy($npc_id)
    {
        $npc = Npc::findOrFail($npc_id);
        $npc->delete();
        return redirect()->route('npcs.index')->with('success', 'NPC deleted successfully.');
    }

    public function all()
    {
        // Fetch all items from the NpcIds model
        $item = new NpcIds();

        // Assuming getAll() returns an associative array or collection
        $allItems = $item->getAllIndexed();

        // Convert to an indexed array (if it isn't already)
        $indexedItems = ($allItems);

        return response()->json(['data' => $indexedItems]);
    }
}
