<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use App\Models\OsrsItem;
use App\Models\NpcItem;
use App\Enums\ItemIds;
use Illuminate\Support\Facades\Http;
class OsrsItemController extends Controller
{
    protected $userAgent = 'AttitudeBot/1.0 (https://attitude.com;)';
    // Display all OSRS items
    public function index(Request $request)
    {
        // Default values
        $perPage = $request->input('per_page', 50); // Default 10 items per page
        $search = $request->input('search', '');    // Search term (if any)
        $sortBy = $request->input('sort_by', 'item_id'); // Default sort by 'item_id'
        $sortOrder = $request->input('sort_order', 'asc'); // Default ascending order

        // Query the OSRS items with filters and sorting
        $items = OsrsItem::when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        // Find NPC items that are missing from the osrs_items table
        $missingItems = NpcItem::leftJoin('osrs_items', 'npc_items.item_id', '=', 'osrs_items.item_id')
            ->whereNull('osrs_items.item_id') // Check where osrs_items.item_id is null
            ->distinct() // Ensure only unique item_ids are selected
            ->pluck('npc_items.item_id'); // Only retrieve the item_id field

        // Return the view with paginated items, missing items, and filters
        return view('osrs-items.index', [
            'items' => $items,
            'missingItems' => $missingItems,
            'perPage' => $perPage,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
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
       
        try {
            // Validate the request
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'value' => 'nullable|integer',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'type' => 'required|string|in:manual,connected,api',  // Assuming these are the valid types
                'parent_id' => 'nullable|integer|exists:osrs_items,id',  // Assuming parent_id references osrs_items table
            ]);
            
        } catch (ValidationException $e) {
            dd($e->errors());
        }
        
    
        // Find and update the item
        $item = OsrsItem::where('item_id', $item_id)->firstOrFail();
        $item->update($data);

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
       
            // Remove the existing media from the 'default' collection if it exists
            $item->clearMediaCollection();
           
            // Add new image to the media collection
            $item->addMediaFromRequest('image')
                ->toMediaCollection();
        }

        return redirect()->route('osrs-items.edit', $item_id)->with('success', 'OSRS Item updated successfully!');
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
    public function findApiItem($itemId)
    {
        // Initialize the ItemIds class
        $items = new ItemIds();
        $index = 0;
    
        // Loop through the items
        foreach ($items->getAll() as $item => $item_id) {
            if ($index < 300) {
                $index++;
                continue;
            }
            // Check if the item already exists in the database
            $existingItem = OsrsItem::where('item_id', $item_id)->first();
    
            // If the item does not exist, fetch it from the API
            if (!$existingItem) {
                // Make the API request to fetch item details
                $priceResponse = Http::withHeaders(['User-Agent' => $this->userAgent])
                    ->get('https://secure.runescape.com/m=itemdb_oldschool/api/catalogue/detail.json', ['item' => $item_id]);
    
                $prices = $priceResponse->json();
                var_dump($prices);
                // If a valid response is returned from the API
                if ($prices && isset($prices['item'])) {
                    $priceValue = $this->convertPrice($prices['item']['current']['price'] ?? 0);
                    // Create or update the item in the database
                    OsrsItem::updateOrCreate([
                        'item_id' => $item_id
                    ], [
                        'name' => $prices['item']['name'],
                        'value' => $priceValue ?? 0,
                        'description' => $prices['item']['description'],
                        'type' => 'api'
                    ]);
                }
                else{
                    OsrsItem::updateOrCreate([
                        'item_id' => $item_id
                    ], [
                        'name' => $item,
                        'value' => 0,
                        'description' => '',
                        'type' => 'manual'
                    ]);
                }
            } else {
                // Item already exists in the database, skip the API request
                continue;
            }
    
            $index++;
    
            // Break the loop after processing 10 items (for throttling purposes)
            if ($index > 350) {
                dd();
            }
        }
    }
    function convertPrice($price)
{
    // Check if the price is a string and contains a suffix
    if (is_string($price)) {
        // Handle the suffixes for thousands (k), millions (m), billions (b)
        if (strpos($price, 'k') !== false) {
            return floatval($price) * 1000; // Convert k to thousand
        } elseif (strpos($price, 'm') !== false) {
            return floatval($price) * 1000000; // Convert m to million
        } elseif (strpos($price, 'b') !== false) {
            return floatval($price) * 1000000000; // Convert b to billion
        }
    }

    // Return the original price if no conversion was needed
    return floatval($price);
}

    
}
