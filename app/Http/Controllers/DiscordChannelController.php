<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
class DiscordChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
                'active' => false,
            ],
            [
                'label' => 'Channels',
                'url' => route('discord-channels.index'),
                'type' => 'activity',
                'active' => true,
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
        $channels = Channel::all();
        return view('discord-channels.index', ['channels' => $channels, 'menuItems'=> $menuItems]);
    }

  
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
