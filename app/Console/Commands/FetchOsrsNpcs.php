<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Npc;
use App\Enums\NpcIds;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
    protected $description = 'Fetch OSRS NPC data and images.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting OSRS NPCs fetching process...');

        $this->findApiNpc();

        $this->info('OSRS NPCs fetching process completed.');
        return 0;
    }

    /**
     * The method that fetches NPCs from the API or CSV.
     */
    public function findApiNpc()
    {
        Cache::forget('npcs_from_csv');
        // Initialize the NpcIds class
        $npcs = new NpcIds();

        // Loop through the NPC items
        foreach ($npcs->getAll() as $npc => $npc_id) {
            // Fetch data by NPC ID
            $npcdb = Npc::where('npc_id', $npc_id)->first();
            $data = $this->getByNpcId($npc_id);
            if($npcdb){
                continue;
            }
            $data = $this->getByNpcId($npc_id);
            
            // Initialize the NPC model
            $npcModel = new Npc();

            if ($data) {
                // Set name, slug, and npc_id
                $npcModel->name = $data['name'];
                $npcModel->slug = $npc;
                $npcModel->npc_id = $npc_id;

                $this->info("Processing NPC: {$data['name']} (Image URL: {$data['image_url']})");

                // Save the NPC model
                $npcModel->save();

                // Save the image if it exists in $data['image_url']
                if (isset($data['image_url'])) {
                    try {
                        $path = 'scrape/npc_images/' . $data['image_url'];

                        // Check if the image exists before proceeding
                        if (Storage::exists($path)) {
                            $npcModel
                                ->addMedia(Storage::path($path)) // Ensure the full file path is passed
                                ->toMediaCollection();
                        } else {
                            // Handle the case where the image does not exist (optional)
                            $this->warn("Image not found: " . $path);
                        }
                       
                    } catch (\Exception $e) {
                        $this->error("Failed to save image for NPC: {$data['name']} - Error: {$e->getMessage()}");
                    }
                }
            } else {
                $this->warn("No data found for NPC: {$npc}");

                // Save NPC with default values
                $npcModel->name = $npc;
                $npcModel->slug = $npc;
                $npcModel->npc_id = $npc_id;
                $npcModel->save();
            }
        }
    }

    /**
     * Fetch NPC data by ID.
     */
    function getByNpcId($npc_id)
    {
        $items = $this->loadItemsFromCsv();
        return $items[$npc_id] ?? null;
    }

    /**
     * Load NPC items from the CSV file.
     */
    function loadItemsFromCsv()
    {
        $items = Cache::get('npcs_from_csv');

        if (!$items) {
            // Path to the CSV file
            $csvPath = 'scrape/npc_images/osrs_npcs.csv';
            $zipPath = 'scrape/npc_images.zip';
            $extractToPath = 'scrape/';

            // Check if the CSV file exists, if not extract the zip
            if (!Storage::exists($csvPath)) {
                if (Storage::exists($zipPath)) {
                    $this->info('Extracting zip file...');

                    $zip = new ZipArchive();
                    if ($zip->open(Storage::path($zipPath)) === true) {
                        $zip->extractTo(Storage::path($extractToPath));
                        $zip->close();
                        $this->info('Zip file extracted successfully.');
                    } else {
                        $this->error('Failed to open the zip file.');
                        return [];
                    }
                } else {
                    $this->error('Zip file not found.');
                    return [];
                }
            }

            // Get the content of the CSV file
            $csvData = Storage::get($csvPath);

            // Convert CSV data into an array
            $rows = array_map('str_getcsv', explode("\n", $csvData));

            // Create an associative array with npc_id as the key
            $items = [];
            foreach ($rows as $row) {
                if (isset($row[1])) {
                    $items[$row[1]] = [
                        'name' => $row[0],       // $row[0] is the NPC name
                        'image_url' => $row[2]   // $row[2] is the image URL
                    ];
                }
            }

            // Cache the data for 24 hours
            Cache::put('npcs_from_csv', $items, 86400);
        }

        return $items;
    }
}
