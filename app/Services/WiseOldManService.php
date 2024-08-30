<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WiseOldManService
{
    protected $baseUrl;
    protected $groupId;

    // Skills
    const SKILLS = [
        'overall',
        'attack',
        'defence',
        'strength',
        'hitpoints',
        'ranged',
        'prayer',
        'magic',
        'cooking',
        'woodcutting',
        'fletching',
        'fishing',
        'firemaking',
        'crafting',
        'smithing',
        'mining',
        'herblore',
        'agility',
        'thieving',
        'slayer',
        'farming',
        'runecrafting',
        'hunter',
        'construction',
    ];

    const BOSSES = [
        'araxxor' => 'Araxxor',
        'abyssal_sire' => 'Abyssal Sire',
        'alchemical_hydra' => 'Alchemical Hydra',
        'artio' => 'Artio',
        'barrows_chests' => 'Barrows Chests',
        'bryophyta' => 'Bryophyta',
        'callisto' => 'Callisto',
        'calvarion' => 'Calvarion',
        'cerberus' => 'Cerberus',
        'chaos_elemental' => 'Chaos Elemental',
        'chaos_fanatic' => 'Chaos Fanatic',
        'commander_zilyana' => 'Commander Zilyana',
        'corporeal_beast' => 'Corporeal Beast',
        'crazy_archaeologist' => 'Crazy Archaeologist',
        'dagannoth_prime' => 'Dagannoth Prime',
        'dagannoth_rex' => 'Dagannoth Rex',
        'dagannoth_supreme' => 'Dagannoth Supreme',
        'deranged_archaeologist' => 'Deranged Archaeologist',
        'duke_sucellus' => 'Duke Sucellus',
        'general_graardor' => 'General Graardor',
        'giant_mole' => 'Giant Mole',
        'grotesque_guardians' => 'Grotesque Guardians',
        'hespori' => 'Hespori',
        'kalphite_queen' => 'Kalphite Queen',
        'king_black_dragon' => 'King Black Dragon',
        'kraken' => 'Kraken',
        'kreearra' => "Kree'arra",
        'kril_tsutsaroth' => "K'ril Tsutsaroth",
        'lunar_chests' => 'Lunar Chests',
        'mimic' => 'Mimic',
        'nex' => 'Nex',
        'nightmare' => 'Nightmare',
        'phosanis_nightmare' => "Phosani's Nightmare",
        'obor' => 'Obor',
        'phantom_muspah' => 'Phantom Muspah',
        'sarachnis' => 'Sarachnis',
        'scorpia' => 'Scorpia',
        'scurrius' => 'Scurrius',
        'skotizo' => 'Skotizo',
        'sol_heredit' => 'Sol Heredit',
        'spindel' => 'Spindel',
        'tempoross' => 'Tempoross',
        'the_gauntlet' => 'The Gauntlet',
        'the_corrupted_gauntlet' => 'The Corrupted Gauntlet',
        'the_leviathan' => 'The Leviathan',
        'the_whisperer' => 'The Whisperer',
        'thermonuclear_smoke_devil' => 'Thermonuclear Smoke Devil',
        'tzkal_zuk' => 'TzKal-Zuk',
        'tztok_jad' => 'TzTok-Jad',
        'vardorvis' => 'Vardorvis',
        'venenatis' => 'Venenatis',
        'vetion' => "Vet'ion",
        'vorkath' => 'Vorkath',
        'wintertodt' => 'Wintertodt',
        'zalcano' => 'Zalcano',
        'zulrah' => 'Zulrah',
    ];
    

    const RAIDS = [
        'tombs_of_amascut' => 'TOA',
        'tombs_of_amascut_expert' => 'TOA',
        'theatre_of_blood' => 'TOB',
        'theatre_of_blood_hard_mode' => 'TOB',
        'chambers_of_xeric' => 'COX',
        'chambers_of_xeric_challenge_mode' => 'COX',
    ];

    // Bosses and Activities
    const ACTIVITIES = [
       
        'league_points',
        'bounty_hunter_hunter',
        'bounty_hunter_rogue',
        'clue_scrolls_all',
        'clue_scrolls_beginner',
        'clue_scrolls_easy',
        'clue_scrolls_medium',
        'clue_scrolls_hard',
        'clue_scrolls_elite',
        'clue_scrolls_master',
        'last_man_standing',
        'pvp_arena',
        'soul_wars_zeal',
        'guardians_of_the_rift',
        'colosseum_glory',
        'ehp',
        'ehb',
    ];
    public function __construct()
    {
        $this->groupId = false;
        $this->baseUrl = 'https://api.wiseoldman.net/v2'; // Base URL of Wise Old Man API
    }

    public function setGroupId($id)
    {
        $this->groupId = $id;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getGroup()
    {
        if (!$this->groupId) {
            throw new \Exception("Group ID not set");
        }

        $response = Http::get("{$this->baseUrl}/groups/{$this->groupId}");
        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getGroupCompetitions()
    {
        if (!$this->groupId) {
            throw new \Exception("Group ID not set");
        }

        $response = Http::get("{$this->baseUrl}/groups/{$this->groupId}/competitions");
        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getGroupMembers()
    {
        $data = $this->getGroup();
  
        if ($data && isset($data['memberships'])) {
            return $data['memberships'];
        }

        return null;
    }

    public function getPlayerGain($player)
    {
        $start_data = '2024-07-31';
        $end_data = '2024-08-31';

        try {
            $response = Http::get("{$this->baseUrl}/players/{$player}/gained", [
                'startDate' => $start_data,
                'endDate' => $end_data
            ]);

            if ($response->successful()) {
       
                // Handle successful response
                $data = $response->json();
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                // Handle non-successful response
                return response()->json(['success' => false, 'message' => 'Error fetching data'], $response->status());
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
