<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Illuminate\Console\Command;
use App\Models\OsrsItem;
use App\Enums\ItemIds;
use Illuminate\Support\Facades\Cache;
use ZipArchive;
use Illuminate\Support\Facades\Http;

class FetchOsrsItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:osrs-items {--force_update=false}';

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

        // Check if force_update flag is set
        $forceUpdate = $this->option('force_update') === 'true';

        if ($forceUpdate) {
            $this->info('Force update is enabled.');
        }

        $this->findApiItem($forceUpdate);

        $this->info('OSRS items fetching process completed.');
        return 0;
    }

    /**
     * The method that fetches items from the API.
     */
    public function findApiItem($force)
    {
        Cache::forget('items_from_csv');
        // Initialize the ItemIds class
        $items = new ItemIds();
        $index = 0;


        // Define the path to your CSV file
        $path = 'scrape/osrs_items.csv'; // Assuming 'items.csv' is the file name in the 'scrape' directory

        // Get the content of the CSV file
        $csvData = Storage::get($path);

        // Convert CSV data into an array
        $rows = array_map('str_getcsv', explode("\n", $csvData));

        // Loop through the items
        foreach ($items->getAll() as $item => $item_id) {
            if ($item_id < 28217){
                continue;
            }
            // Log info about the current item being processed
            $this->info("Processing item: {$item} (ID: {$item_id})");

            // Check if the item already exists in the database
            $existingItem = OsrsItem::where('item_id', $item_id)->first();
            if (!$force && $existingItem && !empty($existingItem->item_id)) {
                $this->itemExists($existingItem, $item, $item_id,);
            } else {
                $this->itemDoesntExists($item, $item_id);
            }

            $index++;
        }
    }

    public function fetchFromOsrs($item_id)
    {
        // Make the API request to fetch item details
        $this->info("Fetching data for item ID: {$item_id} from the API...");
        $response = Http::withHeaders(['User-Agent' => $this->userAgent])
            ->get('https://secure.runescape.com/m=itemdb_oldschool/api/catalogue/detail.json', ['item' => $item_id]);
        return $response->json();
    }

    public function itemDoesntExists($item, $item_id)
    {
        $prices = false; //= $this->fetchFromOsrs($item_id);
        $items = $this->fetchOsrsItems();
        $parent_id = null;
        $image_id = $item_id;
        if (is_array($items) && isset($items[$item_id])) {
            $name =  $items[$item_id]['name'];;
            $description =  $items[$item_id]['examine'];
            $type = 'api';
            $value = $items[$item_id]['value'];
     
        } else {
            if (strpos($item, "_NOTED") !== false){
                // Remove '_NOTED' from the item name to find the base item
                $baseItemSlug = str_replace('_NOTED', '', $item);

                // Search in the database for an item with this slug (base item)
                $baseItem = OsrsItem::where('slug', $baseItemSlug)->first();

                if ($baseItem) {
                    // If the base item is found, set it as the parent_id
                    $parent_id = $baseItem->item_id;
                    $image_id = $parent_id;
                    $name = strtolower(str_replace("_", " ", $baseItem->name));  // Use the base item name
                    $type = 'connected';  // Set type to 'connected'
                    $this->info("Noted item found for base item: {$baseItem->name}, setting as parent.");
                } else {
                    $this->warn("Base item not found for noted item: {$item}");
                    return;
                }
            }
            // If a valid response is returned from the API
            elseif($prices && isset($prices['item'])) {


                $priceValue = $this->convertPrice($prices['item']['current']['price'] ?? 0);
           
                $this->info("Saving item: {$prices['item']['name']} (ID: {$item_id}) with price: {$priceValue}");
                $name = $prices['item']['name'];
                $description = $prices['item']['description'];
                $type = 'api';
                $value = $this->convertPrice($prices['item']['current']['price'] ?? 0);
            } else {
                // Log when no data is available
                $this->warn("No data found for item ID: {$item_id}. Using default values.");
                $name = strtolower(str_replace("_", " ", $item));
                $type = 'manual';
                $description = '';
                $value = 0;
            }
        }

        // If no data, create with default values
        $existingItem = OsrsItem::updateOrCreate([
            'item_id' => $item_id
        ], [
            'name' => $name,
            'slug' => $item,
            'value' => $value,
            'description' => $description,
            'type' => $type,
            'parent_id' => $parent_id
        ]);

        $media = $existingItem->getFirstMedia();
        if (!$media && is_array($items) && isset($items[$image_id])) {
            // Define the path to your image file
            $url = 'https://oldschool.runescape.wiki/images/' . str_replace(" ", "_", $items[$item_id]['icon']);

            // Check if the image URL exists
            $response = Http::head($url);  // Use the HEAD method to just check for the URL existence
            
            if ($response->successful()) {
                $this->warn("Image URL exists: " . $url);
            
                // Add the media to the collection
                $existingItem
                    ->addMediaFromUrl($url)
                    ->toMediaCollection();
            } else {
                $this->checkMedia($existingItem, $item, $item_id);
                $this->warn("Image URL does not exist or is not accessible: " . $url);
            }
            
        } elseif (!$media && $prices && isset($prices['icon_large'])) {
            $this->downloadOsrsImage($existingItem, $prices['icon_large']);
        } else {
            $this->checkMedia($existingItem, $item, $item_id);
        }
    }

    public function itemExists(OsrsItem $existingItem, $item, $item_id)
    {
        $this->checkMedia($existingItem, $item, $item_id);
    }

    public function checkMedia(OsrsItem $existingItem, $item, $item_id)
    {
        $media = $existingItem->getFirstMedia();
        if (!$media) {
            $items = $this->fetchOsrsItems();
            if (is_array($items) && isset($items[$item_id])) {
                // Define the path to your image file
                $url = 'https://oldschool.runescape.wiki/images/' . str_replace(" ", "_", $items[$item_id]['icon']);
                // Check if the image URL exists
                $response = Http::head($url);  // Use the HEAD method to just check for the URL existence
         
                if ($response->successful()) {
                    $this->warn("Image URL exists: " . $url);
                
                    // Add the media to the collection
                    $existingItem
                        ->addMediaFromUrl($url)
                        ->toMediaCollection();
                } else {
                    //$this->checkMedia($existingItem, $item, $item_id);
                    $this->warn("Image URL does not exist or is not accessible: " . $url);
                }
            } 
            else{
                $data = $this->getByItemId($item_id);
                if ($data && $data['image_url']) {
                    // Define the path to your image file
                    $path = 'scrape/item_images/' . $data['image_url'];
    
                    // Check if the image exists before proceeding
                    if (Storage::exists($path)) {
                        $existingItem
                            ->addMedia(Storage::path($path)) // Ensure the full file path is passed
                            ->toMediaCollection();
                    } else {
                        // Handle the case where the image does not exist (optional)
                        $this->warn("Image not found: " . $path);
                    }
                }
            }
            
        }
    }

    public function downloadOsrsImage($existingItem, $gifUrl)
    {

        // Download the GIF image and store it temporarily
        $gifContent = file_get_contents($gifUrl);
        $gifTempPath = 'temp/item.gif'; // You can adjust the path
        Storage::put($gifTempPath, $gifContent);
        $this->info('Saved GIF image');

        // Path for the converted PNG image
        $pngFileName = "item_{$existingItem->item_id}.png";
        $pngPath = Storage::path('temp/' . $pngFileName);


        // Use Spatie Image to convert the GIF to PNG
        Image::load(Storage::path($gifTempPath))
            ->format('png')
            ->save($pngPath);
        $this->info('Saved PNG image');
        // Attach the converted PNG to the media collection
        $this->info('Assiging image to model');
        $existingItem->addMedia($pngPath)
            ->toMediaCollection('images'); // Change 'images' to your media collection name

        // Clean up the temporary GIF and PNG files if needed
        $this->info('Deleting temp GIF image');
        Storage::delete($gifTempPath);
        $this->info('Deleting temp PNG image');
        Storage::delete('temp/' . $pngFileName);
        $this->info('GIF image has been converted to PNG and added to the media collection.');
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

    function getByItemId($item_id)
    {
        // Load the cached items from CSV
        $items = $this->loadItemsFromCsv();

        // Return the image_url if the item_id exists
        return $items[$item_id] ?? null;
    }


    function loadItemsFromCsv()
    {
        // Define the paths
        $zipPath = 'scrape/item_images.zip';
        $csvPath = 'scrape/item_images/osrs_items.csv';
        $extractToPath = 'scrape';

        // Check if the CSV file exists, if not, extract the zip
        if (!Storage::exists($csvPath)) {
            // Check if the zip file exists
            if (Storage::exists($zipPath)) {
                // Extract the zip file
                $zip = new ZipArchive();
                $zipFile = Storage::path($zipPath);

                if ($zip->open($zipFile) === true) {
                    $zip->extractTo(Storage::path($extractToPath));
                    $zip->close();
                } else {
                    throw new Exception("Unable to open the zip file.");
                }
            } else {
                throw new Exception("Zip file not found.");
            }
        }

        // Check if the data is already cached
        $items = Cache::get('items_from_csv');

        if (!$items) {
            // Get the content of the CSV file
            $csvData = Storage::get($csvPath);

            // Convert CSV data into an array
            $rows = array_map('str_getcsv', explode("\n", $csvData));

            // Create an associative array with item_id as the key
            $items = [];
            foreach ($rows as $row) {
                if (isset($row[1])) {
                    $items[$row[1]] = [
                        'name' => $row[0],       // $row[0] is the item name
                        'image_url' => $row[2]   // $row[2] is the image URL
                    ];
                }
            }

            // Cache the data for faster future lookups (for example, for 24 hours)
            Cache::put('items_from_csv', $items, 86400); // Cache for 24 hours (86400 seconds)
        }

        return $items;
    }

    public function fetchOsrsItems()
    {
        // Define the cache key and duration (1 hour = 3600 seconds)
        $cacheKey = 'osrs_items_data';
        $cacheDuration = 3600; // 1 hour in seconds

        // Check if the data is already cached
        return Cache::remember($cacheKey, $cacheDuration, function () {
            // Define the URL to fetch data from
            $url = 'https://prices.runescape.wiki/api/v1/osrs/mapping';

            // Make the HTTP request using the Http facade
            $response = Http::get($url);

            // Check if the request was successful (status code 200)
            if ($response->successful()) {
                // Parse the JSON response
                $data = $response->json();

                // Create an array to hold the mapped data by 'item_id'
                $mappedData = [];

                // Loop through each item and index it by 'id'
                foreach ($data as $item) {
                    $item_id = $item['id'];  // Extract the 'id' from the item
                    $mappedData[$item_id] = $item;  // Use 'item_id' as the key for the item data
                }

                // Return the mapped data array
                return $mappedData;
            } else {
                // Handle any errors or non-success responses
                return false;
            }
        });
    }
}
