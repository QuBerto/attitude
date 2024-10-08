<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit OSRS Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('osrs-items.update', ['osrs_item' =>$item->item_id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="item_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Item ID</label>
                            <input type="number" name="item_id" id="item_id" class="mt-1 dark:bg-gray-700  block w-full" value="{{ $item->item_id }}" disabled>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block dark:bg-gray-700  w-full" value="{{ $item->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="value" class="block text-sm font-medium text-gray-700 dark: dark:text-gray-200">Value</label>
                            <input type="number" name="value" id="value" class="mt-1 dark:bg-gray-700  block w-full" value="{{ $item->value }}">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 dark:bg-gray-700  block w-full">{{ $item->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700  dark:text-gray-200">Type</label>
                            <select name="type" id="type" class="mt-1 block w-full dark:bg-gray-700">
                                <option value="manual" {{ old('type', $item->type ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="connected" {{ old('type', $item->type ?? '') == 'connected' ? 'selected' : '' }}>Connected</option>
                                <option value="api" {{ old('type', $item->type ?? '') == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Parent Item</label>
                            <select name="parent_id" id="parent_id" class="mt-1 block w-full dark:bg-gray-700">
                                <option value="">None</option>
                                @foreach ($items as $parentItem)
                                    <option value="{{ $parentItem->id }}" {{ old('parent_id', $item->parent_id ?? '') == $parentItem->id ? 'selected' : '' }}>
                                        {{ $parentItem->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Current Image</label>
                            @if ($item->getFirstMediaUrl())
                                <img src="{{ $item->getFirstMediaUrl() }}" class="w-32 h-32 rounded" alt="{{ $item->name }}">
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No image available.</p>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Upload New Image</label>
                            <input type="file" name="image" id="image" class="mt-1 block dark:bg-gray-700  w-full">
                        </div>
                    
                        
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
