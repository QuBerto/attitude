<?php

namespace App\Http\Controllers;

use App\Models\DiscordRole;
use Illuminate\Http\Request;

class DiscordRoleController extends Controller
{
    public function index()
    {
        $menuItems = [
            [
                'label' => 'Users',
                'url' => route('discord.users'),
                'type' => 'projects',
                'active' => false,
            ],
            [
                'label' => 'Roles',
                'url' => route('discord-roles.index'),
                'type' => 'deployments',
                'active' => true,
            ],
            [
                'label' => 'Channels',
                'url' => route('discord-channels.index'),
                'type' => 'activity',
                'active' => false,
            ],
            [
                'label' => 'Emojis',
                'url' => route('discord-emojis.index'),
                'type' => 'domains',
                'active' => false,
            ]
            ,
            [
                'label' => 'Check user roles',
                'url' => route('discord-roles.check'),
                'type' => 'domains',
                'active' => false,
            ]
        ];
        $roles = DiscordRole::with('users')->get();
        return view('discord-roles.index', compact('roles', 'menuItems'));
    }

    public function show(DiscordRole $discordRole)
    {
        return view('discord-roles.show', compact('discordRole'));
    }
}
