<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('OSRS Npcs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filters -->
                    <div class="mb-4 flex justify-between items-center">
                        <!-- Search bar -->
                        <!-- Search bar -->
                        <form method="GET" action="{{ route('npcs.index') }}" class="flex items-center space-x-4">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by name or description"
                                class="dark:bg-gray-700 text-gray-900 dark:text-gray-100 border rounded py-2 px-4">

                            <!-- Add the hidden inputs to keep the per_page, sort_by, and sort_order values -->
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'npc_id') }}">
                            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">

                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Search</button>
                        </form>


                        <!-- Items per page dropdown -->
                        <!-- Items per page dropdown -->
                        <form method="GET" action="{{ route('npcs.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'npc_id') }}">
                            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">

                            <select name="per_page" onchange="this.form.submit()"
                                class="border rounded py-2 px-4 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page
                                </option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page
                                </option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page
                                </option>
                            </select>
                        </form>

                    </div>

                    <!-- Table -->
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <!-- Sortable columns -->
                                <th class="py-3 px-4 uppercase font-semibold text-sm">
                                    <a
                                        href="{{ route('npcs.index', ['sort_by' => 'npc_id', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">
                                        Npc ID @if (request('sort_by') === 'npc_id')
                                            ({{ request('sort_order') }})
                                        @endif
                                    </a>
                                </th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Image</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">
                                    <a
                                        href="{{ route('npcs.index', ['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">
                                        Name @if (request('sort_by') === 'name')
                                            ({{ request('sort_order') }})
                                        @endif
                                    </a>
                                </th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach ($npcs as $item)
                                <tr>
                                    <td class="py-3 px-4">{{ $item->npc_id }}</td>
                                    <td class="py-3 px-4">
                               
                                        @if ($item->getFirstMediaUrl('npcs'))
                                            <img src="{{ $item->getFirstMediaUrl('npcs') }}" />
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">{{ $item->name }}</td>
                                
                                    <td class="py-3 px-4">
                                        <a href="{{ route('npcs.edit', $item->npc_id) }}"
                                            class="bg-blue-500 text-white font-bold py-1 px-3 rounded">Edit</a>
                                        <form action="{{ route('npcs.destroy', $item->npc_id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white font-bold py-1 px-3 rounded"
                                                onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination links -->
                    <div class="pagination">
                        {{ $npcs->appends(request()->query())->links() }}
                    </div>

                    @if ($npcs->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 mt-4">No OSRS items found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Item ID</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Create item</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach ($missingItems as $item)
                                <tr>
                                    <td class="py-3 px-4">{{ $item }}</td>
                                    <td class="py-3 px-4">
                                        <!-- Link to the create page with the item_id as a GET parameter -->
                                        {{-- <a href="{{ route('npcs.create', ['npc' => $item]) }}"
                                            class="bg-blue-500 text-white font-bold py-1 px-3 rounded">
                                            Create
                                        </a> 
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($items->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 mt-4">No OSRS items found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}
</x-app-layout>
