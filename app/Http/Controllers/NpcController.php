<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Npc;
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
    public function store(Request $request)
    {
       
    }

    // Display the form for editing an existing item
    public function edit($npc_id)
    {

        $npc = Npc::where('npc_id', $npc_id)->firstOrFail();
        return view('osrs-items.edit', ['item' => $npc]);
    }

    // Update an existing OSRS item
    public function update(Request $request, $npc_id)
    {
        // Validate the request
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find and update the item
        $item = Npc::where('npc_id', $npc_id)->firstOrFail();
        $item->update($data);

        return redirect()->route('npcs.index')->with('success', 'OSRS Item updated successfully!');
    }

    // Delete an OSRS item
    public function destroy($npc_id)
    {
        $item = Npc::where('npc_id', $npc_id)->firstOrFail();
        $item->delete();

        return redirect()->route('npcs.index')->with('success', 'OSRS Item deleted successfully!');
    }
}
