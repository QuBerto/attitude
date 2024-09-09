<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OsrsItem;
use App\Enums\ItemIds;
use Illuminate\Support\Facades\Http;

class FetchOsrsItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:osrs-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch OSRS items from the API and store them in the database';

    /**
     * The user agent to use for API requests.
     */
    protected $userAgent = 'Your-User-Agent-Here';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting OSRS items fetching process...');
        
        $this->findApiItem();
        
        $this->info('OSRS items fetching process completed.');
        return 0;
    }

    /**
     * The method that fetches items from the API.
     */
    public function findApiItem()
    {
        // Initialize the ItemIds class
        $items = new ItemIds();
        $index = 0;

        // Loop through the items
        foreach ($items->getAll() as $item => $item_id) {
            
            // Check if the item already exists in the database
            $existingItem = OsrsItem::where('item_id', $item_id)->first();

            // Log info about the current item being processed
            $this->info("Processing item: {$item} (ID: {$item_id})");

            // If the item does not exist, fetch it from the API
            if (!$existingItem) {
                // Make the API request to fetch item details
                $this->info("Fetching data for item ID: {$item_id} from the API...");
                $priceResponse = Http::withHeaders(['User-Agent' => $this->userAgent])
                    ->get('https://secure.runescape.com/m=itemdb_oldschool/api/catalogue/detail.json', ['item' => $item_id]);

                $prices = $priceResponse->json();
                
                // If a valid response is returned from the API
                if ($prices && isset($prices['item'])) {
                    $priceValue = $this->convertPrice($prices['item']['current']['price'] ?? 0);
                    
                    $this->info("Saving item: {$prices['item']['name']} (ID: {$item_id}) with price: {$priceValue}");
                    
                    // Create or update the item in the database
                    OsrsItem::updateOrCreate([
                        'item_id' => $item_id
                    ], [
                        'name' => $prices['item']['name'],
                        'value' => $priceValue ?? 0,
                        'description' => $prices['item']['description'],
                        'type' => 'api'
                    ]);
                } else {
                    // Log when no data is available
                    $this->warn("No data found for item ID: {$item_id}. Using default values.");

                    // If no data, create with default values
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
                // Log when the item already exists
                $this->info("Item ID: {$item_id} already exists. Skipping API fetch.");
            }

            $index++;
        }
    }

    /**
     * Converts a price string with k, m, b to numeric form.
     */
    public function convertPrice($price)
    {
        // Check if the price is a string and contains a suffix
        if (is_string($price)) {
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
