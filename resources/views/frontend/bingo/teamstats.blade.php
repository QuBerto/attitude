@isset($team)
    @php
        // Initialize arrays to hold the data and totals
        $data = [];
        $totals = [];

        // Populate the data array with account data and calculate totals
        foreach ($team->users as $user) {
            foreach ($user->rsAccounts as $account) {
                foreach ($account->meta as $meta) {
                    if ($meta->value != 0 && strpos($meta->key, '_kills_gained') !== false) {
                        // Extract the key name and remove '_kills_gained'
                        $keyName = ucfirst(str_replace('_', ' ', str_replace('_kills_gained', '', $meta->key)));
                        
                        // Initialize the arrays if not already set
                        if (!isset($data[$keyName])) {
                            $data[$keyName] = [];
                        }
                        if (!isset($totals[$keyName])) {
                            $totals[$keyName] = 0;
                        }

                        // Add the value to the data and totals arrays
                        $data[$keyName][$account->username] = $meta->value;
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
    @endphp

    <table class="min-w-full divide-y divide-gray-700">
        <thead>
            <tr>
                <th></th>
                @foreach ($usernames as $username)
                    <th class="py-3.5 pl-4 pr-3 text-left text-center text-sm rs-yellow sm:pl-0 rs-font">{{ $username }}</th>
                @endforeach
                <th class="py-3.5 pl-4 pr-3 text-left text-sm  sm:pl-0 text-center  rs-font">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @foreach ($data as $keyName => $values)
                <tr>
                    <td class="whitespace-nowrap py-4  pl-4 pr-3 text-sm font-medium text-white sm:pl-0 rs-font">{{ $keyName }}</td>
                    @foreach ($usernames as $username)
                        @php
                        $class = '';
                        @endphp
                        @isset ($values[$username])
                            @php
                            $class = ' text-white'
                            @endphp
                        @endisset
                        <td class="whitespace-nowrap py-4 text-center pl-4 pr-3 text-sm font-medium sm:pl-0  text-white rs-font{{$class}}">
                            {{ $values[$username] ?? "-" }}
                        </td>
                    @endforeach
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-center text-sm font-medium text-white sm:pl-0 rs-font">
                        {{ $totals[$keyName] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endisset
