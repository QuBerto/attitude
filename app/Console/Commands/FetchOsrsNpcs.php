<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Npc;
use App\Enums\NpcIds;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class FetchOsrsNpcs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:osrs-npcs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting OSRS items fetching process...');

        $this->findApiNpc();

        $this->info('OSRS items fetching process completed.');
        return 0;
    }

    /**
     * The method that fetches items from the API.
     */
    public function findApiNpc()
    {
        // Initialize the ItemIds class
        $npcs = new NpcIds();


        // Loop through the NPC items
        foreach ($npcs->getAll() as $npc => $npc_id) {
            // Fetch data by NPC ID
            $npcdb = Npc::where('npc_id', $npc_id)->first();
            if($npcdb){
                continue;
            }
            $data = $this->getByNpcId($npc_id);
            
            // Initialize the NPC model
            $npcModel = new Npc();

            if ($data) {
                // If data is found, set name and slug
                $npcModel->name = $data['name'];
                $npcModel->slug = $npc;
                $npcModel->npc_id = $npc_id;
                
                // Log info about the current item being processed
                $this->info("Processing item: {$data['name']} (Image URL: {$data['image_url']})");

                // Save the NPC model to the database
                $npcModel->save();

                // Save the image if it exists in $data['image_url']
                if (isset($data['image_url'])) {
                    try {
                        $npcModel
                            ->addMediaFromUrl($data['image_url'])
                            ->toMediaCollection('npcs'); // Save to 'npcs' media collection
                    } catch (\Exception $e) {
                        // Log the error if something goes wrong with image saving
                        $this->error("Failed to save image for NPC: {$data['name']} - Error: {$e->getMessage()}");
                    }
                }
            } else {
                // If no data is found, default to saving the NPC's name and slug as $npc
                $npcModel->name = $npc;
                $npcModel->slug = $npc;
                $npcModel->npc_id = $npc_id;
                // Log that no data was found
                $this->warn("No data found for NPC: {$npc}");

                // Save the NPC model with default values
                $npcModel->save();
            }
        }
    }

    function getByNpcId($item_id)
    {
        // Load the cached items from CSV
        $items = $this->loadItemsFromCsv();

        // Return the image_url if the item_id exists
        return $items[$item_id] ?? null;
    }


    function loadItemsFromCsv()
    {
        // Check if the data is already cached
        $items = Cache::get('npcs_from_csv');

        if (!$items) {
            // Define the path to your CSV file
            $path = 'scrape/osrs_npcs.csv';

            // Get the content of the CSV file
            $csvData = Storage::get($path);

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
            Cache::put('npcs_from_csv', $items, 86400); // Cache for 24 hours (86400 seconds)
        }

        return $items;
    }
}
