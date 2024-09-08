<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('OSRS Items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('osrs-items.create') }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded">
                            Add New Item
                        </a>
                    </div>
                    
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Item ID</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Name</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Value</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Description</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach ($items as $item)
                                <tr>
                                    <td class="py-3 px-4">{{ $item->item_id }}</td>
                                    <td class="py-3 px-4">{{ $item->name }}</td>
                                    <td class="py-3 px-4">{{ $item->value }}</td>
                                    <td class="py-3 px-4">{{ $item->description }}</td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('osrs-items.edit', $item->item_id) }}" class="bg-blue-500 text-white font-bold py-1 px-3 rounded">
                                            Edit
                                        </a>

                                        <form action="{{ route('osrs-items.destroy', $item->item_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white font-bold py-1 px-3 rounded" onclick="return confirm('Are you sure you want to delete this item?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($items->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 mt-4">No OSRS items found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
