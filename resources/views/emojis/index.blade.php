<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Emojis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('emojis.create') }}" class="bg-green-500 text-white py-2 px-4 rounded">Add New Emoji</a>

                    @if(session('success'))
                        <div class="bg-green-500 text-white py-2 px-4 rounded mt-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full bg-white dark:bg-gray-800 mt-4">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">ID</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Name</th>
                                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach ($emojis as $emoji)
                                <tr>
                                    <td class="w-1/4 py-3 px-4">{{ $emoji->emoji_id }}</td>
                                    <td class="w-1/4 py-3 px-4">{{ $emoji->name }}</td>
                                    <td class="w-1/4 py-3 px-4">
                                        <a href="{{ route('emojis.edit', $emoji->id) }}" class="bg-blue-500 text-white py-1 px-3 rounded">Edit</a>
                                        <form action="{{ route('emojis.destroy', $emoji->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded" onclick="return confirm('Are you sure you want to delete this emoji?')">Delete</button>
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
