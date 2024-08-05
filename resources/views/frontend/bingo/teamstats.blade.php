@isset($team)
    @php
        // Initialize arrays to hold the data and totals
        $data = [];
        $totals = [];

        // Populate the data array with account data and calculate totals
        foreach ($team->users as $user) {
            foreach ($user->rsAccounts as $account) {
                foreach ($account->meta as $meta) {
                    if ($meta->value != 0 && (strpos($meta->key, '_kills_gained') !== false || $meta->key == 'ehb_value_gained')) {
                        // Extract the key name and rename ehb_value_gained to EHB
                        if ($meta->key == 'ehb_value_gained') {
                            $keyName = 'EHB';
                        } else {
                            $keyName = ucfirst(str_replace('_', ' ', str_replace('_kills_gained', '', $meta->key)));
                        }

                        // Initialize the arrays if not already set
                        if (!isset($data[$keyName])) {
                            $data[$keyName] = [];
                        }
                        if (!isset($totals[$keyName])) {
                            $totals[$keyName] = 0;
                        }

                        // Add the value to the data and totals arrays
                        $data[$keyName][$account->username] = round($meta->value, 1);
                        $totals[$keyName] += $meta->value;
                    }
                }
            }
        }

        // Get all account usernames
        $usernames = [];
        foreach ($team->users as $user) {
            foreach ($user->rsAccounts as $account) {
                $usernames[] = $account->username;
            }
        }
        $usernames = array_unique($usernames);

        // Sort the keys alphabetically but put EHB first
        $sortedKeys = array_keys($data);
        usort($sortedKeys, function($a, $b) {
            if ($a == 'EHB') return -1;
            if ($b == 'EHB') return 1;
            return strcmp($a, $b);
        });
    @endphp

    <table class="min-w-full divide-y divide-gray-700">
        <thead>
            <tr>
                <th></th>
                @foreach ($usernames as $username)
                    <th class="py-3.5 pl-4 pr-3 text-left text-center text-sm rs-yellow sm:pl-0">{{ $username }}</th>
                @endforeach
                <th class="py-3.5 pl-4 pr-3 text-left text-sm sm:pl-0 text-center">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @foreach ($sortedKeys as $keyName)
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{ $keyName }}</td>
                    @foreach ($usernames as $username)
                        @php
                            $class = isset($data[$keyName][$username]) ? ' text-white' : '';
                        @endphp
                        <td class="whitespace-nowrap py-4 text-center pl-4 pr-3 text-sm font-medium sm:pl-0 text-white {{ $class }}">
                            {{ $data[$keyName][$username] ?? "-" }}
                        </td>
                    @endforeach
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-center text-sm font-medium text-white sm:pl-0">
                        {{ round($totals[$keyName], 1) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endisset
