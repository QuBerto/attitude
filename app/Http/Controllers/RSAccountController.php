<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RSAccount;
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
        $query = RSAccount::query();

        // Handle search
        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        // Handle sorting
        $sortField = $request->get('sort_field', 'username');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $accounts = $query->paginate(25)->appends($request->all());

        return view('frontend.members.members', compact('accounts'));
    }

    
}
