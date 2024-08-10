<h3 class="text-lg font-semibold mt-6">Tiles</h3>
<div id="tiles" class="accordion space-y-4">
    @foreach ($bingoCard->tiles as $index => $tile)
        <div class="accordion-item tile p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm" data-tile-id="{{ $tile->id }}">
            <h4 class="accordion-header font-semibold text-xl mb-3 cursor-pointer flex justify-between items-center">
                <span>#{{ $index + 1 }} {{ $tile->title }}</span>
                <svg class="accordion-icon w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </h4>
            <div class="accordion-content hidden transition-all duration-300">
                <form class="tile-form space-y-6 mt-4" method="POST" action="{{ route('tiles.update', $tile->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col md:flex-row md:space-x-4">
                        <div class="w-full">
                            <label for="image_{{ $tile->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Image') }}</label>
                            <div class="flex items-center space-x-4">
                                <label for="image_{{ $tile->id }}" class="cursor-pointer">
                                    @if ($tile->getFirstMediaUrl('*'))
                                        <img id="image-preview_{{ $tile->id }}" width="100" src="{{ $tile->getFirstMediaUrl('*') }}" class="rounded-md shadow-md">
                                    @else
                                        <svg id="image-preview-icon_{{ $tile->id }}" xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18V3H3zm3 8l3 3 6-6M13 13h8v8h-8v-8z" />
                                        </svg>
                                    @endif
                                </label>
                                <input id="image_{{ $tile->id }}" class="hidden" type="file" name="image" accept="image/*" onchange="previewImage(event, '{{ $tile->id }}')" />
                            </div>
                        </div>
                        
                        <div class="w-full">
                            <label for="title_{{ $tile->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
                            <input id="title_{{ $tile->id }}" class="block w-full p-3 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="text" name="title" value="{{ $tile->title }}" required />
                        </div>
                      
                    </div>
                    <div class="w-full">
                        <label for="tasks_{{ $tile->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Tasks') }}</label>
                        <ul id="tasks-list_{{ $tile->id }}" class="mb-4 space-y-2">
                            @foreach ($tile->tasks as $task)
                                <li class="flex justify-between items-center p-2 bg-gray-100 dark:bg-gray-700 rounded-md shadow-sm">
                                    <span>{{ $task->description }}</span>
                                    <button type="button" class="remove-task-button text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-600" data-task-id="{{ $task->id }}">
                                        &times;
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="flex items-center space-x-2">
              
                            <input type="text" id="new-task_{{ $tile->id }}" class="flex-grow p-3 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" placeholder="Add new task" />
                            <button type="button" class="add-task-button bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded" data-tile-id="{{ $tile->id }}">
                                {{ __('Add Task') }}
                            </button>
                        </div>
                    </div>
                    <div class="w-full">
                        <label for="boss_{{ $tile->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Bosses') }}</label>
                        <ul id="boss-list_{{ $tile->id }}" class="mb-4 space-y-2">
                            @isset($tile->bosses)
                            @foreach ($tile->bosses as $boss)
                                <li class="flex justify-between items-center p-2 bg-gray-100 dark:bg-gray-700 rounded-md shadow-sm">
                                    <span>{{ $boss }}</span>
                                    <button type="button" class="remove-boss-button text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-600" data-task-id="{{ $task->id }}">
                                        &times;
                                    </button>
                                </li>
                            @endforeach
                            @endisset
                        </ul>
                        
                        <div class="flex items-center space-x-2">
                            <select id="new-boss_{{ $tile->id }}" class="flex-grow p-3 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                                <option value="" disabled selected>{{ __('Select a boss') }}</option>
                                @foreach($bosses as $boss)
                                    <option value="{{ $boss }}">{{ ucwords(str_replace('_', ' ', $boss)) }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="add-boss-button bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded" data-tile-id="{{ $tile->id }}">
                                {{ __('Add Boss') }}
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4 w-full">
                        <button type="button" class="save-tile-button ml-4 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded" data-tile-id="{{ $tile->id }}">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>

<script>
    document.querySelectorAll('.accordion-header').forEach(header => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.accordion-icon');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                content.classList.add('block');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                content.classList.remove('block');
                icon.classList.remove('rotate-180');
            }
        });
    });
</script>
