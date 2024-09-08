<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OsrsItem;
use App\Models\NpcItem;
use Illuminate\Support\Facades\Http;
class OsrsItemController extends Controller
{
    protected $userAgent = 'AttitudeBot/1.0 (https://attitude.com;)';
    // Display all OSRS items
    public function index()
    {
        $items = OsrsItem::all();
         // Use a left join to find NPC items that are missing from the osrs_items table
            // Use a left join and select distinct item_id where osrs_items.item_id is null
        $missingItems = NpcItem::leftJoin('osrs_items', 'npc_items.item_id', '=', 'osrs_items.item_id')
        ->whereNull('osrs_items.item_id') // Check where osrs_items.item_id is null
        ->distinct() // Ensure only unique item_ids are selected
        ->pluck('npc_items.item_id'); // Only retrieve the item_id field
   
        return view('osrs-items.index', ['items' => $items, 'missingItems' => $missingItems]);
    }

    // Display the form for creating a new item
    public function create()
    {
        $items = OsrsItem::all();
        return view('osrs-items.create', ['items' => $items]);
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
        $items = OsrsItem::all();
        $item = OsrsItem::where('item_id', $item_id)->firstOrFail();
        return view('osrs-items.edit', ['item' => $item, 'items'=> $items]);
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

    // Get NPC items that are not present in the OSRS items table
    // Get distinct NPC item IDs that are not present in the OSRS items table
    public function getMissingOsrsItems()
    {
        // Use a left join and select distinct item_id where osrs_items.item_id is null
        $missingItems = NpcItem::leftJoin('osrs_items', 'npc_items.item_id', '=', 'osrs_items.item_id')
            ->whereNull('osrs_items.item_id') // Check where osrs_items.item_id is null
            ->distinct() // Ensure only unique item_ids are selected
            ->pluck('npc_items.item_id'); // Only retrieve the item_id field

        return response()->json($missingItems, 200);
    }

    public function findApiItem( $itemId ){
        $priceResponse = Http::withHeaders(['User-Agent' => $this->userAgent])
            ->get('https://secure.runescape.com/m=itemdb_oldschool/api/catalogue/detail.json', ['item' => $itemId]);
        $prices = $priceResponse->json();
        if ($prices){
    
            OsrsItem::create([
                'item_id' => $itemId,
                'name' => $prices['item']['name'],
                'value' => $prices['item']['current']['price'] ?? 0,
                'description' => $prices['item']['description'],
            ]);
            return true;
        }
        return false;
    }
}
