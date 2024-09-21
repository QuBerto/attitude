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

       
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            
        
                            @if(session('success'))
                                <div class="bg-green-500 text-white py-2 px-4 rounded mt-4">
                                    {{ session('success') }}
                                </div>
                            @endif
        
                            <table class="min-w-full bg-white dark:bg-gray-800 mt-4">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm text-left">ID</th>
                                        <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm text-left">Name</th>
                                        <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Icon</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 dark:text-gray-300">
                                    @foreach ($emojis as $emoji)
                                        <tr>
                                            <td class="w-1/4 py-3 px-4">{{ $emoji->emoji_id }}</td>
                                            <td class="w-1/4 py-3 px-4">
                                                <div class="flex">
                                              
                                                <div>{{ $emoji->name }}</div>
                                                </div>
                                            </td>
                                            <td class="w-1/4 py-3 px-4">
                                                <img class="w-8 mr-2" src="{{$emoji->getFirstMediaUrl('images')}}"/>
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   

</x-app-layout>



