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
                                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Username</th>
                                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Nick</th>
                                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">RSN</th>
                                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Roles</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="w-1/3 py-3 px-4">{{ $user->username }}</td>
                                    <td class="w-1/3 py-3 px-4">{{ $user->nick }}</td>
                                    <td class="w-1/3 py-3 px-4">
                                    @foreach($user->rsAccounts as $account)
                                    {{ $account->username }}
                                    @endforeach
                                </td>
                                    <td class="w-1/3 py-3 px-4 flex" style="max-width:250px;">
                                        @foreach ($user->roles as $role)
                                            <span style="background-color:{{$role->color}}" class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">{{ $role->name }}</span>
                                        @endforeach
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
