<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-4">
                        <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                        <input id="name" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="text" name="name" required />
                    </div>
                    <div class="mt-4">
                        <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <textarea id="description" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" name="description"></textarea>
                    </div>
                    <div class="mt-4">
                        <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Start Date') }}</label>
                        <input id="start_date" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="datetime-local" name="start_date" required />
                    </div>
                    <div class="mt-4">
                        <label for="end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('End Date') }}</label>
                        <input id="end_date" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="datetime-local" name="end_date" required />
                    </div>
                    <div class="mt-4">
                        <label for="image" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Image') }}</label>
                        <input id="image" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="file" name="image" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="ml-4 bg-blue-500 dark:bg-blue-700 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-blue-900">
                            {{ __('Create Event') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
