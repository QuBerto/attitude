<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bingo Cards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('bingo-cards.create') }}" class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-900 text-white font-bold py-2 px-4 rounded">
                        {{ __('Create Bingo Card') }}
                    </a>
                    <ul class="mt-4">
                        @foreach ($bingoCards as $card)
                            <li class="mt-2 flex justify-between items-center">
                                <a href="{{ route('bingo-cards.show', $card->id) }}" class="text-blue-500 dark:text-blue-400">
                                    {{ $card->name }}
                                </a>
                                <div>
                                    <a href="{{ route('bingo-cards.edit', $card->id) }}" class="bg-yellow-500 hover:bg-yellow-700 dark:bg-yellow-600 dark:hover:bg-yellow-800 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('bingo-cards.destroy', $card->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
