
@php
// if(!isset($teamData)){
//     return;
// }
    // Group tiles by their bosses
    $groupedTiles = [];
    foreach ($bingoCard->tiles as $tile) {
        $bosses = json_decode($tile->bosses, true);

        if ($bosses) {
            sort($bosses); // Sort the bosses to ensure the order does not matter
            $bossKey = implode(',', $bosses);
        } else {
            $bossKey = '-';
        }

        if (!isset($groupedTiles[$bossKey])) {
            $groupedTiles[$bossKey] = [];
        }
        $groupedTiles[$bossKey][] = $tile;
    }
@endphp

<div class="overflow-x-auto w-full">
    <table class="min-w-full shadow-md rounded-lg w-full">
        <thead>
            <tr>
                <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300">Img</th>
                <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300">Tile(s)</th>
                <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300">Boss</th>
                @foreach ($teamsData as $team)
                    @if (isset($team['team_name']))
                        <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300 text-center">
                            {{ $team['team_name'] }}
                        </th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedTiles as $bossKey => $tiles)
                @php
                    $bosses = explode(',', $bossKey);
                    $bossCount = count($bosses);
                @endphp
                <tr>
                    <th class="py-3 px-6 text-left text-sm text-gray-100" rowspan="{{ $bossCount + 1 }}">
                        <!-- Display images for all tiles in this group -->
                        @foreach ($tiles as $tile)
                            <img style="height:30px; margin-bottom: 5px;" src="{{ $tile->getFirstMediaUrl('*') }}" alt="{{ $tile->title }} image" class="mt-2 h-auto">
                        @endforeach
                    </th>
                    <th class="py-3 px-6 text-left text-sm text-gray-100" rowspan="{{ $bossCount + 1 }} text-xl">
                        <!-- Display titles for all tiles in this group -->
                        @foreach ($tiles as $tile)
                            {{ $tile->title }}<br>
                        @endforeach
                    </th>
                </tr>
                
                @foreach ($bosses as $index => $boss)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="py-3 px-6 text-sm font-medium text-white ">{{ ucwords(str_replace('_', ' ', $boss)) }}</td>
                        @foreach ($teamsData as $team)
                            @if (isset($team['team_name']))
                                <td class="py-3 px-6 text-sm text-gray-100 text-center text-xl">
                                    @php
                                        // Find the kills for the current boss and team
                                        $kills = 0;
                                        foreach ($team['data'] as $player) {
                                            if (isset($player['data'][$boss])) {
                                                $kills += $player['data'][$boss];
                                            }
                                        }
                                    @endphp
                                    @if($kills)
                                    {{ $kills }}
                                    @else
                                    -
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
