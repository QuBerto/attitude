<div class="overflow-x-auto w-full">
    <table class="min-w-full shadow-md rounded-lg w-full">
        <thead>
            <tr>
                <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300">Img</th>
                <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300">Tile</th>
                <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300">Boss</th>
                @foreach ($teamsData as $team)
                    @if (isset($team['team_name'])) <!-- Ensure we only print team rows -->
                        <th class="py-3 px-6 text-left font-bold uppercase text-sm text-gray-600 dark:text-gray-300 text-center">
                            {{ $team['team_name'] }}
                        </th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($bingoCard->tiles as $tile)
                @php
                    $bosses = json_decode($tile->bosses);
                    if ($bosses){
                        $bossCount = count($bosses);
                    }
                    else{
                        $bossCount = 1;
                    }
                    
                @endphp
                <tr>
                    <th class="py-3 px-6 text-left text-sm text-gray-100" rowspan="{{ $bossCount + 1 }}">
                        
                        <!-- Example image, adjust the src attribute as needed -->
                        <img style="height:30px;" src="{{ $tile->getFirstMediaUrl("*") }}" alt="{{ $tile->title }} image" class="mt-2 h-auto">
                    </th>
                    <th class="py-3 px-6 text-left text-sm text-gray-100" rowspan="{{ $bossCount + 1 }}">
                        {{ $tile->title }}
                    </th>
                </tr>
                
                @isset($bosses)
                    @foreach ($bosses as $index => $boss)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            @if ($index == 0) <!-- Add empty td on first boss row to align with the image -->
                                <td class="py-3 px-6 text-sm font-medium text-white text-xl">{{ ucwords(str_replace('_', ' ', $boss)) }}</td>
                            @else
                                <td class="py-3 px-6 text-sm font-medium text-white text-xl">{{ ucwords(str_replace('_', ' ', $boss)) }}</td>
                            @endif
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
                                        {{ $kills }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    @else
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="py-3 px-6 text-sm font-medium text-white">{{ ucwords(str_replace('_', ' ', '-')) }}</td>
                        @foreach ($teamsData as $team)
                                @if (isset($team['team_name']))
                                    <td class="py-3 px-6 text-sm text-gray-100 text-center">
                                       -
                                    </td>
                                @endif
                            @endforeach
                    </tr>
                @endisset
            @endforeach
        </tbody>
    </table>
</div>
