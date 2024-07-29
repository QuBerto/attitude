<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Discord User: ') . $discordUser->username }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p><strong>Username:</strong> {{ $discordUser->username }}</p>
                    <p><strong>Nick:</strong> {{ $discordUser->nick }}</p>
                    <p><strong>Discriminator:</strong> {{ $discordUser->discriminator }}</p>
                    <p><strong>Roles:</strong></p>
                    <ul>
                        @foreach ($discordUser->roles as $role)
                            <li>{{ $role->name }}</li>
                        @endforeach
                    </ul>

                    <h3 class="text-lg font-semibold mt-4">Assigned Players</h3>
                    <ul>
                        @foreach ($discordUser->rsAccounts as $account)
                            <li class="flex justify-between items-center">
                                {{ $account->username }}
                                <form method="POST" action="{{ route('discord-users.unassign-player', [$discordUser->id, $account->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-primary-button class="ml-4 bg-red-500 hover:bg-red-700">
                                        {{ __('Unassign') }}
                                    </x-primary-button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                    <h3 class="text-lg font-semibold mt-4">Assign a Player</h3>
                    <form method="POST" action="{{ route('discord-users.assign-player', $discordUser->id) }}">
                        @csrf
                        <div class="mt-4">
                            <label for="search" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Search Player') }}</label>
                            <input type="text" id="search" onkeyup="filterOptions()" placeholder="Search for players.." class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                        </div>
                        <div class="mt-4">
                            <label for="rs_account_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Select Player') }}</label>
                            <select id="rs_account_id" name="rs_account_id" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                                @foreach ($rsAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Assign') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterOptions() {
            const search = document.getElementById('search').value.toLowerCase();
            const options = document.getElementById('rs_account_id').options;

            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                const text = option.text.toLowerCase();

                if (text.includes(search)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        }
    </script>
</x-app-layout>
