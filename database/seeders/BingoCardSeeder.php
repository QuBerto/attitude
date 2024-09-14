<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BingoCard;
use App\Models\Team;
use App\Models\DiscordUser;
use Illuminate\Support\Facades\Storage;

class BingoCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $bingoCard = BingoCard::all();
        if($bingoCard){
            return;
        }
        // Create a bingo card
        $bingoCard = BingoCard::create(['name' => 'OSRS Boss Bingo']);

        // Items from OSRS bosses
        $osrsBossItems = [
            ['name' => 'Any wilderness pet (no ele)', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/180px-Vetion_Jr1.png', 'tasks' => ['Obtain a wilderness pet'] ],
            ['name' => '5 pieces from Perilous moons', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/1024px-Eyatlallis_glyph1.png', 'tasks' => ['Obtain 1/5 pieces', 'Obtain 2/5 pieces', 'Obtain 3/5 pieces', 'Obtain 4/5 pieces', 'Obtain 5/5 pieces'] ],
            ['name' => 'Any Nightmare Unique', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/The_Nightmare1.png', 'tasks' => ['Any Nightmare Unique'] ],
            ['name' => 'Full armadyl', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/100px-Armadyl_chestplate_detail1.png', 'tasks' => ['Armadyl Chestplate', 'Armadyl skirts', 'Armadyl Boots'] ],
            ['name' => 'Any Virtus Piece', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/Virtus_mask_chathead1.png', 'tasks' => ['Any Virtus Piece'] ],
            ['name' => 'Any Crystal from Cerberus', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/1024px-Cerberus1.png', 'tasks' => ['Any Crystal from Cerberus'] ],
            ['name' => '1 Quiver and 1 Infernal cape or 30 Fire capes', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Infernal_cape_detail1.png', 'tasks' => ['1 Quiver', '1 Infernal', '30 Fire capes'] ],
            ['name' => 'Ancestral / Justiciar / Masori', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/90px-Ancestral_robe_bottom_detail1.png', 'tasks' => ['Ancestral / Justiciar / Masori'] ],
            ['name' => 'Sarachnis cudgel', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Sarachnis_cudgel_detail1.png', 'tasks' => ['Sarachnis cudgel'] ],
            ['name' => 'SOTD / Zammy spear', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/98px-Staff_of_the_dead_detail1.png', 'tasks' => ['SOTD / Zammy spear'] ],
            ['name' => 'Create Venator bow from scratch', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/130px-Venator_bow_detail1.png', 'tasks' => ['Obtain 1/5 pieces', 'Obtain 2/5 pieces', 'Obtain 3/5 pieces', 'Obtain 4/5 pieces', 'Obtain 5/5 pieces'] ],
            ['name' => 'Any Sigil (corp)', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/1280px-Corporeal_Critter-e1708625693236.png', 'tasks' => ['Any Sigil (corp)'] ],
            ['name' => 'Full bandos', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/120px-Bandos_armour_equipped_male1.png', 'tasks' => ['Bandos Chestplate', 'Bandos tassets', 'Bandos Boots'] ],
            ['name' => 'ACB', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Armadyl_crossbow_detail1.png', 'tasks' => ['ACB'] ],
            ['name' => 'Any DT2 Vestige', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/130px-Ultor_vestige_detail1.png', 'tasks' => ['Any DT2 Vestige'] ],
            ['name' => 'Tbow / Scythe / Shadow', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Twisted_bow_detail1.png', 'tasks' => ['Tbow / Scythe / Shadow'] ],
            ['name' => 'Beserker & Archer & Seers & Warriors', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Berserker_ring_detail1.png', 'tasks' => ['Berserker', 'Archer', 'Seers', 'Warriors'] ],
            ['name' => 'Complete set from barrows', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/120px-Ahrims_robes_equipped_male1.png', 'tasks' => ['Obtain 1/5 pieces', 'Obtain 2/5 pieces', 'Obtain 3/5 pieces', 'Obtain 4/5 pieces']],
            ['name' => 'Dragon pickaxe from KQ', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/179px-Kq_head_detail1.png', 'tasks' => ['Dragon pickaxe from KQ'] ],
            ['name' => '3x Unsired', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/800px-Unsired_detail1.png', 'tasks' => ['1/3 Unsired', '2/3 Unsired', '3/3 Unsired'] ],
            ['name' => 'Enhanced Weapon Seed, Armour Seed', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Crystalline_Hunllef1.png', 'tasks' => ['Enhanced Weapon Seed, Armour Seed'] ],
            ['name' => 'tanF & magicF & Serp', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/250px-Zulrah_serpentine1.png', 'tasks' => ['tanF', 'magicF', 'Serp'] ],
            ['name' => 'Nihil Horn / Zambraces / Any Torva Piece', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/270px-Nex1.png', 'tasks' => ['Nihil Horn / Zambraces / Any Torva Piece'] ],
            ['name' => 'Create voidwaker from scratch', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Voidwaker_detail1.png', 'tasks' => ['1/3', '2/3', '3/3'] ],
            ['name' => 'Dex / Arcane / Avernic / Fang / Lightbearer', 'image' => 'https://www.attitude-osrs.com/wp-content/uploads/2024/02/150px-Osmumtens_fang_detail1.png', 'tasks' => ['Dex / Arcane / Avernic / Fang / Lightbearer'] ],
        ];

        
        // Add tiles to the bingo card and create corresponding tasks
        foreach ($osrsBossItems as $item) {
            // $imagePath = $this->downloadImage($item['image'], 'tiles');
            $tile = $bingoCard->tiles()->create(['title' => $item['name']]);

            $tile
               ->addMediaFromUrl($item['image'])
               ->toMediaCollection('tiles');

            foreach ($item['tasks'] as $task) {
                // Create a task for the tile
                $tile->tasks()->create([
                    'description' => $task,
                ]);
            }
        }

        // Create teams
        $teamA = Team::create(['name' => 'Team A']);
        $teamB = Team::create(['name' => 'Team B']);
        $teamC = Team::create(['name' => 'Team C']);

        $bingoCard->teams()->attach($teamA->id);
        $bingoCard->teams()->attach($teamB->id);
        $bingoCard->teams()->attach($teamC->id);
        // Fetch existing Discord users
        $discordUsers = DiscordUser::whereNotNull('nick')->take(45)->get();

        // Split users into two groups, up to 15 per team
        $usersForTeamA = $discordUsers->splice(0, min(15, $discordUsers->count()));
        $usersForTeamB = $discordUsers->splice(0, min(15, $discordUsers->count()));
        $usersForTeamC = $discordUsers->splice(0, min(15, $discordUsers->count()));
        // Assign users to teams
        $teamA->users()->attach($usersForTeamA->pluck('id')->toArray());
        $teamB->users()->attach($usersForTeamB->pluck('id')->toArray());
        $teamC->users()->attach($usersForTeamC->pluck('id')->toArray());
    }
      /**
     * Download image from URL and save to storage.
     *
     * @param string $url
     * @param string $folder
     * @return string
     */
    private function downloadImage($url, $folder)
    {
         // Ensure the folder exists
        Storage::disk('public')->makeDirectory($folder);

        // Generate a unique file name
        $fileName = $folder . '/' . basename($url);

        // Check if the file already exists
        if (Storage::disk('public')->exists($fileName)) {
            return $fileName;
        }

        // Get the image content
        $imageContents = file_get_contents($url);

        // Save the image to storage
        Storage::disk('public')->put($fileName, $imageContents);

        return $fileName;
    }
}
