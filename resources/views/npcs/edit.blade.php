<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit NPC') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Success / Error Messages -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="bg-red-500 text-white p-4 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
<!-- Back to Index Button -->
                    <a href="{{ route('npcs.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded inline-block mt-2 mb-4">Back to Index</a>
                    <!-- Edit NPC Form -->
                    <form method="POST" action="{{ route('npcs.update', $npc->npc_id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- NPC ID (read-only) -->
                        <div class="mb-4">
                            <label for="npc_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NPC ID</label>
                            <input type="text" name="npc_id" id="npc_id" value="{{ $npc->npc_id }}" readonly
                                   class="dark:bg-gray-700 border border-gray-300 rounded py-2 px-4 text-gray-900 dark:text-gray-100 w-full" />
                        </div>

                        <!-- NPC Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NPC Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $npc->name) }}"
                                   class="dark:bg-gray-700 border border-gray-300 rounded py-2 px-4 text-gray-900 dark:text-gray-100 w-full" />
                        </div>

                        <!-- Current Image -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Image</label>
                            @if ($npc->getFirstMediaUrl('npcs'))
                                <img src="{{ $npc->getFirstMediaUrl('npcs') }}" class="w-32 h-32 rounded" alt="{{ $npc->name }}">
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No image available.</p>
                            @endif
                        </div>

                        <!-- New Image Upload -->
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload New Image</label>
                            <input type="file" name="image" id="image"
                                   class="dark:bg-gray-700 border border-gray-300 rounded py-2 px-4 text-gray-900 dark:text-gray-100 w-full" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between mt-6">
   
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save Changes</button>
                        </div>
                    </form>

                    

                    <!-- Delete Button -->
                    <form action="{{ route('npcs.destroy', $npc->npc_id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded"
                                onclick="return confirm('Are you sure you want to delete this NPC?')">Delete NPC</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
