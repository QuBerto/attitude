<?php

namespace App\Http\Controllers;

use App\Models\DiscordRole;
use Illuminate\Http\Request;

class DiscordRoleController extends Controller
{
    public function index()
    {
        $roles = DiscordRole::with('users')->get();
        return view('discord-roles.index', compact('roles'));
    }

    public function show(DiscordRole $discordRole)
    {
        return view('discord-roles.show', compact('discordRole'));
    }
}
