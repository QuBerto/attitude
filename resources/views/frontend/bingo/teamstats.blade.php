@isset($teamData)

    <table class="min-w-full divide-y divide-gray-700">
        <thead>
            <tr>
                <th></th>
                @foreach ($teamData['usernames'] as $username)
                    <th class="py-3.5 pl-4 pr-3 text-left text-center rs-yellow sm:pl-0">{{ $username }}</th>
                @endforeach
                <th class="py-3.5 pl-4 pr-3 text-left sm:pl-0 text-center">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @foreach ($teamData['sortedKeys'] as $keyName)
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium text-white sm:pl-0">{{ $keyName }}</td>
                    @foreach ($teamData['usernames'] as $username)
               
                        @php
                    
                            $class = isset($teamData['data'][$keyName][$username]) ? ' text-white' : '';
                        @endphp
                        <td class="whitespace-nowrap py-4 text-center pl-4 pr-3 font-medium sm:pl-0 text-white {{ $class }}">
                            {{ $teamData['data'][$keyName][$username] ?? "-" }}
                        </td>
                    @endforeach
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-center font-medium text-white sm:pl-0">
                        {{ round($teamData['totals'][$keyName], 1) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endisset
