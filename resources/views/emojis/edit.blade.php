<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Emoji') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('emojis.update', $emoji->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="emoji_id">Emoji ID</label>
                            <input type="text" name="emoji_id" id="emoji_id" value="{{ $emoji->emoji_id }}" class="border rounded py-2 px-4" required>
                        </div>

                        <div class="mt-4">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" value="{{ $emoji->name }}" class="border rounded py-2 px-4" required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Update Emoji</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
