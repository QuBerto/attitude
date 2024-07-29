<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <label for="site_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Site Name') }}</label>
                            <input id="site_name" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="site_description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Site Description') }}</label>
                            <textarea id="site_description" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" name="site_description" required>{{ old('site_description', $settings->site_description) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="maintenance_mode" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Maintenance Mode') }}</label>
                            <input id="maintenance_mode" class="mt-1" type="checkbox" name="maintenance_mode" {{ $settings->maintenance_mode ? 'checked' : '' }} />
                        </div>

                        <div class="mt-4">
                            <label for="logo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Logo') }}</label>
                            <input id="logo" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="file" name="logo" />
                            @if ($settings->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="h-20">
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="ml-4 bg-blue-500 dark:bg-blue-700 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-blue-900">
                                {{ __('Update Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
