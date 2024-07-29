<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bingo Card: ') . $bingoCard->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Teams</h3>
                    <div id="teams" class="accordion">
                        @foreach ($bingoCard->teams as $team)
                            <div class="accordion-item team p-4 border border-gray-300 dark:border-gray-600 rounded-md mb-4" data-team-id="{{ $team->id }}">
                                <h4 class="accordion-header font-semibold mb-2 text-center cursor-pointer">
                                    {{ $team->name }}
                                </h4>
                                <div class="accordion-content hidden">
                                    <h5 class="font-semibold mb-2 text-center">Members</h5>
                                    <ul>
                                        @foreach ($team->users as $user)
                                            <li class="flex justify-between items-center">
                                                {{ $user->nick }}
                                                <button class="delete-member-button text-red-500" data-user-id="{{ $user->id }}" data-team-id="{{ $team->id }}">
                                                    {{ __('Delete') }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    
                                    <form class="add-member-form flex flex-wrap gap-4 items-baseline mt-4" method="POST" action="{{ route('teams.addMember', $team->id) }}">
                                        @csrf
                                        <div>
                                            <label for="discord_user_id_{{ $team->id }}" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Add Member') }}</label>
                                            <select id="discord_user_id_{{ $team->id }}" name="discord_user_id" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md">
                                                @foreach ($discordUsers as $user)
                                                    <option value="{{ $user->id }}">{{ $user->nick }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex items-center justify-end mt-4">
                                            <button type="button" class="add-member-button ml-4 bg-green-500 dark:bg-green-700 text-white font-bold py-2 px-4 rounded hover:bg-green-700 dark:hover:bg-green-900" data-team-id="{{ $team->id }}">
                                                {{ __('Add') }}
                                            </button>
                                        </div>
                                    </form>
                                    <button class="delete-team-button mt-4 bg-red-500 dark:bg-red-700 text-white font-bold py-2 px-4 rounded hover:bg-red-700 dark:hover:bg-red-900" data-team-id="{{ $team->id }}">
                                        {{ __('Delete Team') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                

                    <div class="mt-4">
                        <h4 class="font-semibold mb-2">Add New Team</h4>
                        <form id="add-team-form" class="flex flex-wrap gap-4 items-baseline" method="POST" action="{{ route('teams.store',  ['card' => $bingoCard->id]) }}">
                            @csrf
                            <input type="hidden" value="{{$bingoCard->id}}" name="card"/>
                            <div class="mt-4">
                                <label for="team_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Team Name') }}</label>
                                <input id="team_name" class="block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" type="text" name="name" required />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" id="add-team-button" class="ml-4 bg-blue-500 dark:bg-blue-700 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-blue-900">
                                    {{ __('Add Team') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    @include('bingo-cards.tiles')
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add accordion functionality
            document.querySelectorAll('.accordion-header').forEach(header => {
                header.addEventListener('click', function() {
                    const accordionItem = this.parentElement;
                    const accordionContent = accordionItem.querySelector('.accordion-content');
                    const allContents = document.querySelectorAll('.accordion-content');
                    
                    allContents.forEach(content => {
                        if (content !== accordionContent) {
                            content.classList.add('hidden');
                        }
                    });
                    
                    accordionContent.classList.toggle('hidden');
                });
            });
            
            // Add member using AJAX
            document.querySelectorAll('.add-member-button').forEach(button => {
                button.addEventListener('click', function() {
                    const teamId = this.dataset.teamId;
                    const select = document.getElementById(`discord_user_id_${teamId}`);
                    const userId = select.value;
        
                    fetch(`{{ url('teams') }}/${teamId}/addMember`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ discord_user_id: userId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                });
            });
        
            // Delete member with confirmation
            document.querySelectorAll('.delete-member-button').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this member?')) {
                        const teamId = this.dataset.teamId;
                        const userId = this.dataset.userId;
        
                        fetch(`{{ url('teams') }}/${teamId}/removeMember`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ user_id: userId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                    }
                });
            });
        
            // Delete team with confirmation
            document.querySelectorAll('.delete-team-button').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this team?')) {
                        const teamId = this.dataset.teamId;
        
                        fetch(`{{ url('teams') }}/${teamId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                    }
                });
            });

            // Add task using AJAX
            document.querySelectorAll('.add-task-button').forEach(button => {
                button.addEventListener('click', function () {
                    const tileId = this.dataset.tileId;
                    const newTaskInput = document.getElementById(`new-task_${tileId}`);
                    const taskDescription = newTaskInput.value.trim();

                    if (taskDescription) {
                        fetch('{{ route('tasks.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                tile_id: tileId,
                                description: taskDescription
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const tasksList = document.getElementById(`tasks-list_${tileId}`);
                                const newTaskLi = document.createElement('li');
                                newTaskLi.textContent = taskDescription;
                                tasksList.appendChild(newTaskLi);
                                newTaskInput.value = '';
                            } else {
                                alert('Error adding task.');
                            }
                        });
                    } else {
                        alert('Please enter a task description.');
                    }
                });
            });

            // Complete task using AJAX
            document.querySelectorAll('.complete-task-button').forEach(button => {
                button.addEventListener('click', function () {
                    const taskId = this.dataset.taskId;
                    const tileId = this.dataset.tileId;
                    const teamId = document.querySelector(`.team[data-team-id]:not(.hidden)`).dataset.teamId;
                    const userId = document.querySelector(`.team[data-team-id="${teamId}"] .delete-member-button`).dataset.userId;

                    fetch(`/tasks/${taskId}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            discord_user_id: userId,
                            team_id: teamId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Task completed successfully!');
                            location.reload();
                        } else {
                            alert('Error completing task.');
                        }
                    });
                });
            });

            // Save tile using AJAX
            document.querySelectorAll('.save-tile-button').forEach(button => {
                button.addEventListener('click', function () {
                    const tileId = this.dataset.tileId;
                    const form = document.querySelector(`.tile[data-tile-id="${tileId}"] .tile-form`);
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Tile saved successfully!');
                        } else {
                            alert('Error saving tile.');
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
