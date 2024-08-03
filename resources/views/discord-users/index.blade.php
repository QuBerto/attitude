<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Discord Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Username (nick)</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Roles</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">RSN</th>
                                
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="w-1/4 py-3 px-4">{{ $user->username }} ({{ $user->nick }})</td>
                                    <td class="w-1/4 py-3 px-4" style="max-width:250px;">
                                        @foreach ($user->roles as $role)
                                            <div style="background-color:{{ $role->color }};" class="p-2 mb-1 rounded">{{ $role->name }}</div>
                                        @endforeach
                                    </td>
                                    <td class="w-1/4 py-3 px-4">
                                        @foreach($user->rsAccounts as $account)
                                            {{ $account->username }}
                                        @endforeach
                                        <form method="POST" action="{{ route('discord-users.assign-player', $user->id) }}" class="mt-2 flex items-center space-x-2">
                                            @csrf
                                            <input type="hidden" name="discord_user_id" value="{{ $user->id }}">
                                            <select name="rs_account_id" class="block appearance-none bg-gray-800 border border-gray-700 text-white py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-gray-700 focus:border-gray-500">
                                                @foreach($rsAccounts as $rsaccount)
                                                    <option value="{{ $rsaccount->id }}">{{ $rsaccount->username }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                                Submit
                                            </button>
                                        </form>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
