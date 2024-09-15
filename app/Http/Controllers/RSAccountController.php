<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\RSAccount;
use App\Models\Emoji;
class RSAccountController extends Controller
{
    public function index()
    {
        $accounts = RSAccount::all();
        return view('rs-accounts.index', compact('accounts'));
    }

    public function show(RSAccount $rSAccount)
    {
        return view('rs-accounts.show', compact('rSAccount'));
    }

    public function frontend(Request $request)
    {
      // Retrieve all emojis in a single query
        $emojiNames = [
            'Owner', 'Deputy_owner', 'Administrator', 'Moderator', 'dragon_rank', 
            'Rune_Bar', 'Adamant', 'Mithril_bar', 'Gold_bar', 'Steel_bar', 
            'Iron_bar', 'Bronze_bar', 'Friend_clan_rank', 'ironman', 'Hardcore_ironman'
        ];

        $emojis = Emoji::whereIn('name', $emojiNames)->get()->keyBy('name');

        // Map the emojis to the rank keys
        $ranks = [
            'owner' => $emojis->get('Owner'),
            'deputy_owner' => $emojis->get('Deputy_owner'),
            'completionist' => $emojis->get('Administrator'),
            'moderator' => $emojis->get('Moderator'),
            'dragon' => $emojis->get('dragon_rank'),
            'rune' => $emojis->get('Rune_Bar'),
            'adamant' => $emojis->get('Adamant'),
            'mithril' => $emojis->get('Mithril_bar'),
            'gold' => $emojis->get('Gold_bar'),
            'steel' => $emojis->get('Steel_bar'),
            'iron' => $emojis->get('Iron_bar'),
            'bronze' => $emojis->get('Bronze_bar'),
            'member' => $emojis->get('Friend_clan_rank'),
            'ironman' => $emojis->get('ironman'),
            'hardcore' => $emojis->get('Hardcore_ironman'),
        ];

    
        // Get the roles in the correct order based on the ranks array
        $rankOrder = array_keys($ranks);
    
        $query = RSAccount::query()
    ->leftJoin('player_meta', function ($join) {
        $join->on('r_s_accounts.id', '=', 'player_meta.r_s_accounts_id')
             ->where('player_meta.key', '=', DB::raw("'overall_level'")); // Pass 'overall_level' directly
    })
    ->select('r_s_accounts.*', 'player_meta.value as overall_level');

    
        // Handle search
        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }
    
        // Handle sorting
        $sortField = $request->get('sort_field', 'role');
        $sortDirection = $request->get('sort_direction', 'asc');
    
        // Sort by meta field (overall_level)
        if ($sortField === 'overall_level') {
            $query->orderBy('player_meta.value', $sortDirection);
        } elseif ($sortField === 'role') {
            // Sort accounts based on the custom rank order for 'role'
            $query->orderByRaw('FIELD(role, "' . implode('", "', $rankOrder) . '") ' . $sortDirection);
        } elseif($sortDirection){
                $sortDirection = 'asc';
            
            // Default sorting for other fields
            $query->orderBy($sortField, $sortDirection);
        }
    
        // Handle pagination
        $accounts = $query->paginate(25)->appends($request->all());
      
        // Pass ranks and accounts to the view
        return view('frontend.members.members', compact('accounts', 'ranks'));
    }
    
    
    

    
}
