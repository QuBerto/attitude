<x-app-layout>
    <div class="flex gap-5">


        @isset($menuItems)
            <div class="hidden xl:flex xl:inset-y-0 xl:z-50 xl:flex xl:w-72 xl:flex-col">

                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-black/10 px-6 ring-1 ring-white/5">
                    <div class="flex h-16 shrink-0 items-center">
                        Discord
                    </div>
                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col gap-y-7">
                            <li>
                                <x-sidebar-menu :items="$menuItems" />
                            </li>
                    </nav>
                </div>
            </div>
        @endisset

        <div>
            <table class="min-w-full bg-gray-800 text-left text-sm">
                <thead>
                    <tr>
                        <th class="py-2 px-4">Username</th>
                        <th class="py-2 px-4">Role</th>
                        <th class="py-2 px-4">RS</th>
                        <th class="py-2 px-4">Steel+</th>
                        <th class="py-2 px-4">No doubles</th>
                        <th class="py-2 px-4">Rs matches DC</th>
                        <th class="py-2 px-4">Suggested Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $user)
                        <tr class="border-b border-gray-700">
                            <td class="py-2 px-4">{{ $user['username'] }}</td>
                            <td class="py-2 px-4">
                                <ul class="list-disc list-inside">
                                    @foreach($user['discord_roles'] as $role)
                                        <li>{{ ucfirst($role) }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-2 px-4">
                                <ul class="list-disc list-inside">
                                    @foreach($user['accounts'] as $account)
                                        <li>{{$account['username']}} {{ ucfirst($account['role']) }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-2 px-4">
                                {{ $user['has_steel_or_higher'] ? 'Yes' : 'No' }}
                            </td>
                            <td class="py-2 px-4">
                                {{ $user['has_lower_roles'] ? 'Yes' : 'No' }}
                            </td>
                            <td class="py-2 px-4">
                                {{ $user['all_rs_accounts_match'] ? 'No' : 'Yes' }}
                            </td>
                            <td class="py-2 px-4">
                                {{ $user['suggested_discord_role'] ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
