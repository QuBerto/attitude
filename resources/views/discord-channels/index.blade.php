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
            <table class="min-w-full bg-white dark:bg-gray-800">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/2 py-3 px-4 uppercase font-semibold text-sm text-left" >Channel ID</th>
                        <th class="w-1/2 py-3 px-4 uppercase font-semibold text-sm text-left">Channel</th>

                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    @foreach ($channels as $channel)
                        <tr>
                            <td class="w-1/2 py-3 px-4">{{ $channel->channel_id }}</td>
                            <td class="w-1/2 py-3 px-4">{{ $channel->name }}</td>
                            
                        </tr>
                        <tr>
                          
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
