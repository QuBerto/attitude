<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('RS Accounts') }}
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
                                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Display Name</th>
                                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Overall Experience</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach ($accounts as $account)
                                <tr>
                                    <td class="w-1/3 py-3 px-4">{{ $account->username }}</td>
                                    <td class="w-1/3 py-3 px-4">{{ $account->display_name }}</td>
                                    <td class="w-1/3 py-3 px-4">{{ $account->overall_experience }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
