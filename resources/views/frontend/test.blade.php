@extends('layouts.frontend')
@section('content')
    <style>
        .osrs-container {
            max-width: 100%;
  
            background-color: #3B342C;
            border: 2px solid #6E6659; /* Outer border */
            border-radius: 10px; /* Outer border radius */
            box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.5); /* Shadow */
            padding: 20px; /* Padding to make room for inner border */
            position: relative; /* Important for positioning the ::before element */

        }
        .osrs-wrapper {
            display: flex;
            gap: 20px; /* Space between kills and top loot sections */
        }


        .osrs-container::before {
            content: '';
            position: absolute;
            top: 10px;
            bottom: 10px;
            left: 10px;
            right: 10px;
            border: 1px solid #A49D8F; /* Inner light brown border */
            border-radius: 8px; /* Slightly smaller radius for the inner border */
            pointer-events: none; /* Prevent the pseudo-element from interfering with clicks */
            z-index: -1; /* Place behind the container content */
        }

        .kills-section {
            width: 75%; /* 3/4 of the container */
        }

        .top-loot-section {
            width: 25%; /* 1/4 of the container */
        }

        /* Styling for alternating row colors */
        .table tbody tr:nth-child(odd) {
            background-color: #453E37; /* Darker brown for odd rows */
        }

        .table tbody tr:nth-child(even) {
            background-color: #3B342C; /* Original background color for even rows */
        }

        /* Styling for gold, silver, and bronze positions */
        .leaderboard-index {
            font-weight: bold;
        }

        .leaderboard-index.gold {
            color: #FFD700; /* Gold */
        }

        .leaderboard-index.silver {
            color: #C0C0C0; /* Silver */
        }

        .leaderboard-index.bronze {
            color: #CD7F32; /* Bronze */
        }

    </style>

    <div class="osrs-container">
        <div class="my-2 leaderboard-head">
            <div class="flex items-center justify-center gap-3">
                @if ($spoon && $spoon->getFirstMediaUrl('images'))
                    <img class="h-8" src="{{ $spoon->getFirstMediaUrl('images') }}" />
                @endif
                <h1 class="rs-font rs-yellow">Attitude Monthly Golden Spoon Leaderboard</h1>
            </div>
        </div>
        <div class="osrs-wrapper">
        <!-- Kills Leaderboard Section (3/4) -->
        
        <div class="kills-section">
            

            <div class="leaderboard-body">
                <table class="table w-full">
                    <thead class="rs-font text-white">
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">User</th>
                            <th class="text-left">NPC</th>
                            <th>Loot</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody class="text-center text-white">
                        @foreach ($kills as $kill)
                            <tr>
                                <!-- Index with Gold, Silver, and Bronze Colors -->
                                <td class="text-left leaderboard-index
                                    @if ($loop->iteration == 1) gold
                                    @elseif ($loop->iteration == 2) silver
                                    @elseif ($loop->iteration == 3) bronze
                                    @endif">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-left text-white">{{ $kill->discordUser->username }}</td>
                                <td class="text-left">
                                    @if ($kill->npc)
                                        @if ($kill->npc->getFirstMediaUrl('*'))
                                            <div class="flex">  
                                                <div><img src="{{ $kill->npc->getFirstMediaUrl('*') }}" class="h-8"></div>
                                            </div>
                                        @elseif($kill->npc->name)
                                            {{ str_replace('_', ' ', $kill->npc->name) }}
                                        @else
                                            {{ $kill->npc_id }}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <ul class="flex justify-center">
                                        @foreach ($kill->items as $item)
                                            <li>
                                                @if ($item->osrsItem)
                                                    @if ($item->osrsItem->getFirstMediaUrl('*'))
                                                        <div class="inline-flex justify-center">
                                                            <div class="absolute"><span class="relative"
                                                                    style="left: 10px;
                                                                            color: yellow;
                                                                            font-weight: 100;
                                                                            font-size: 16px;">{{ $item->quantity }}</span>
                                                            </div><img src="{{ $item->osrsItem->getFirstMediaUrl('*') }}">
                                                        </div>
                                                    @else
                                                        {{ $item->osrsItem->name }} (x{{ $item->quantity }})
                                                    @endif
                                                @else
                                                    {{ $item->item_id }} (x{{ $item->quantity }})
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $kill->ge_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top 10 Total Loot Section (1/4) -->
        <div class="top-loot-section">
            <div class="my-4 leaderboard-head">
                <h2 class="rs-font rs-yellow text-center">Top 10</h2>
            </div>

            <div class="leaderboard-body">
                <table class="table w-full ">
                    <thead class="rs-font text-white">
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">User</th>
                            <th class="text-left">Total Loot Value</th>
                        </tr>
                    </thead>
                    <tbody class="text-center text-white">
                        @foreach ($totalLootPerUser as $userLoot)
                            <tr>
                                <!-- Index with Gold, Silver, and Bronze Colors -->
                                <td class="text-left leaderboard-index
                                    @if ($loop->iteration == 1) gold
                                    @elseif ($loop->iteration == 2) silver
                                    @elseif ($loop->iteration == 3) bronze
                                    @endif">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-left text-white">{{ $userLoot->discordUser->username ?? 'Unknown User' }}</td>
                                <td>{{ number_format($userLoot->total_loot) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
       
    </div>
    <x-utc-time />
</div>
@stop
