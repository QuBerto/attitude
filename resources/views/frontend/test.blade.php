@extends('layouts.frontend')
@section('content')
    <style>
        .osrs-container {
            max-width: 800px;
            min-height: 400px;
            background-color: #3B342C;
            /* Dark brown background color */
            border: 2px solid #6E6659;
            /* Slightly lighter brown border */
            border-radius: 10px;
            /* Rounded corners */
            box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.5);
            /* Soft shadow around */
            position: relative;
            padding: 10px;
        }

        .osrs-container::before {
            content: '';
            position: absolute;
            top: 5px;
            bottom: 5px;
            left: 5px;
            right: 5px;
            border: 1px solid #A49D8F;
            /* Inner light brown border */
            border-radius: 8px;
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

        <div class="leaderboard-body">
            <table class="table w-full rs-font">
                <thead class=" text-white">
                    <tr>
                        <th>
                            User
                        </th>
                        <th>
                            NPC
                        </th>
                        <th>
                            Loot
                        </th>
                        <th>
                            Value
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center rs-font">


                    @foreach ($kills as $kill)
                        <tr>
                            <td>
                                {{ $kill->discordUser->username }}
                            </td>
                            <td>
                                @if ($kill->npc)
                                    {{ strtolower(str_replace('_', ' ', $kill->npc->slug)) }}
                                @else
                                    {{ $kill->npc_id }}
                                @endif
                            </td>
                            <td>
                                <ul>
                                    @foreach ($kill->items as $item)
                                        <li>
                                            @if ($item->osrsItem)
                                                @if ($item->osrsItem->getFirstMediaUrl('*'))
                                                    <div class="flex justify-center">
                                                        <div>
                                                            <div class="absolute"><span class="relative"
                                                                    style="left: 28px;
  color: yellow;
  font-weight: 100;
  font-size: 18px;">{{ $item->quantity }}</span>
                                                            </div><img src="{{ $item->osrsItem->getFirstMediaUrl('*') }}">
                                                        </div>
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
                            <td>
                                {{ $kill->ge_price }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@stop
