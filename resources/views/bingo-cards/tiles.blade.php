<h3 class="text-lg font-semibold mt-6">Tiles</h3>
<div id="tiles" class="accordion">
    @foreach ($bingoCard->tiles as $index => $tile)
        <div class="accordion-item tile p-4 border border-gray-300 dark:border-gray-600 rounded-md mb-4" data-tile-id="{{ $tile->id }}">
            <h4 class="accordion-header font-semibold mb-2 text-center cursor-pointer">
                Tile {{ $index + 1 }}
            </h4>
            <div class="accordion-content hidden">
                <form class="tile-form flex flex-wrap gap-4 mt-4" method="POST" action="{{ route('tiles.update', $tile->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="w-full flex flex-wrap md:flex-nowrap gap-4">
                        <div class="flex-grow">
                            <label for="title_{{ $tile->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
                            <input id="title_{{ $tile->id }}" class="block w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="text" name="title" value="{{ $tile->title }}" required />
                        </div>
                        <div class="flex-grow">
                            <label for="image_{{ $tile->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Image') }}</label>
                            <input id="image_{{ $tile->id }}" class="block w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="file" name="image" />
                        </div>
                    </div>
                    <div class="w-full mt-4">
                        <label for="tasks_{{ $tile->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tasks') }}</label>
                        <ul id="tasks-list_{{ $tile->id }}" class="mb-2">
                            @foreach ($tile->tasks as $task)
                                <li class="flex justify-between items-center">
                                    {{ $task->description }}
                                </li>
                            @endforeach
                        </ul>
                        <div class="flex gap-2">
                            <input type="text" id="new-task_{{ $tile->id }}" class="flex-grow p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" placeholder="Add new task" />
                            <button type="button" class="add-task-button bg-green-500 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-900 text-white font-bold py-2 px-4 rounded" data-tile-id="{{ $tile->id }}">
                                {{ __('Add Task') }}
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4 w-full">
                        <button type="button" class="save-tile-button ml-4 bg-blue-500 dark:bg-blue-700 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-blue-900" data-tile-id="{{ $tile->id }}">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
