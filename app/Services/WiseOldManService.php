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

    // Bosses and Activities
    const BOSSES = [
        'abyssal_sire',
        'alchemical_hydra',
        'artio',
        'barrows_chests',
        'bryophyta',
        'callisto',
        'calvarion',
        'cerberus',
        'chaos_elemental',
        'chaos_fanatic',
        'commander_zilyana',
        'corporeal_beast',
        'crazy_archaeologist',
        'dagannoth_prime',
        'dagannoth_rex',
        'dagannoth_supreme',
        'deranged_archaeologist',
        'duke_sucellus',
        'general_graardor',
        'giant_mole',
        'grotesque_guardians',
        'hespori',
        'kalphite_queen',
        'king_black_dragon',
        'kraken',
        'kreearra',
        'kril_tsutsaroth',
        'lunar_chests',
        'mimic',
        'nex',
        'nightmare',
        'phosanis_nightmare',
        'obor',
        'phantom_muspah',
        'sarachnis',
        'scorpia',
        'scurrius',
        'skotizo',
        'sol_heredit',
        'spindel',
        'tempoross',
        'the_gauntlet',
        'the_corrupted_gauntlet',
        'the_leviathan',
        'the_whisperer',
        'thermonuclear_smoke_devil',
        'tzkal_zuk',
        'tztok_jad',
        'vardorvis',
        'venenatis',
        'vetion',
        'vorkath',
        'wintertodt',
        'zalcano',
        'zulrah',
    ];

    const RAIDS = [
        'tombs_of_amascut',
        'tombs_of_amascut_expert',
        'theatre_of_blood',
        'theatre_of_blood_hard_mode',
        'chambers_of_xeric',
        'chambers_of_xeric_challenge_mode',
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
