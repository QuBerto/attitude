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
        // Define emoji ranks
        $ranks = [
            'owner' => Emoji::where('name', 'Owner')->first(),
            'deputy_owner' => Emoji::where('name', 'Deputy_owner')->first(),
            'completionist' => Emoji::where('name', 'Administrator')->first(),
            'moderator' => Emoji::where('name', 'Moderator')->first(),
            'dragon' => Emoji::where('name', 'dragon_rank')->first(),
            'rune' => Emoji::where('name', 'Rune_Bar')->first(),
            'adamant' => Emoji::where('name', 'Adamant')->first(),
            'mithril' => Emoji::where('name', 'Mithril_bar')->first(),
            'gold' => Emoji::where('name', 'Gold_bar')->first(),
            'steel' => Emoji::where('name', 'Steel_bar')->first(),
            'iron' => Emoji::where('name', 'Iron_bar')->first(),
            'bronze' => Emoji::where('name', 'Bronze_bar')->first(),
            'member' => Emoji::where('name', 'Friend_clan_rank')->first(),
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
