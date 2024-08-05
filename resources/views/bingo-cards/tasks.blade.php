<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex gap-5">
                    @foreach($allteams as $allteam)
                        <h2><a href="{{route('tasks.team', ['team' => $allteam->id, 'bingo' => "1"])}}">{{$allteam->name}}</a></h2>
                    @endforeach
                    </div>
                    @foreach ($teams as $team)

                        <h3 class="text-lg font-semibold mt-6">{{ $team->name }} Tasks</h3>
                        <table class="min-w-full bg-white dark:bg-gray-800 mb-6">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">{{ __('Task') }}</th>
                                    <th class="px-4 py-2">{{ __('Completed') }}</th>
                                    {{-- <th class="px-4 py-2">{{ __('Item id') }}</th> --}}
                                    <th class="px-4 py-2">{{ __('User') }}</th>
                                    <th class="px-4 py-2">{{ __('Description') }}</th>
                                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    @php
                                        $completion = $task->completions->where('team_id', $team->id)->first();
                                    @endphp
                                    <tr>
                                        <td class="border px-4 py-2">{{ $task->description }}<br><small>{{$task->tile->title}}</small></td>
                                        <td class="border px-4 py-2">
                                            {{ $completion ? 'Yes' : 'No' }}
                                        </td>
                                        {{-- <td class="border px-4 py-2">
                                            <input id="task-item-id-{{ $task->id }}-{{ $team->id }}" class="task-description bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 " type="number" name="item_id" value="{{ $completion->item_id ?? '' }}">
                                        </td> --}}
                                        <td class="border px-4 py-2">
                                            <select class="user-select block mt-1 w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md" data-task-id="{{ $task->id }}" data-team-id="{{ $team->id }}">
                                                <option value="">{{ __('Select User') }}</option>
                                                @foreach ($team->users as $user)
                                                    <option value="{{ $user->id }}" {{ $completion && $completion->discord_user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->nick }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <input id="task-description-{{ $task->id }}-{{ $team->id }}" class="task-description bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 " type="text" name="description" value="{{ $completion->description ?? '' }}">
                                        </td>
                                        <td class="border px-4 py-2">
                                            <button class="task-action-button bg-green-500 text-white px-4 py-2 rounded" data-task-id="{{ $task->id }}" data-team-id="{{ $team->id }}" data-action="complete">
                                                {{ $completion ? __('Save                                                                                               ') : __('Complete Task') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.task-action-button').forEach(button => {
                button.addEventListener('click', function () {
                    const taskId = this.dataset.taskId;
                    const teamId = this.dataset.teamId;
                    const action = this.dataset.action;
                    const itemInput = document.getElementById(`task-item-id-${taskId}-${teamId}`);
                    const item = itemInput ? itemInput.value : '';
                    const descriptionInput = document.getElementById(`task-description-${taskId}-${teamId}`);
                    const description = descriptionInput ? descriptionInput.value : '';
                    const userSelect = document.querySelector(`.user-select[data-task-id="${taskId}"][data-team-id="${teamId}"]`);
                    const userId = userSelect.value;

                    if (!userId && action === 'complete') {
                        alert('Please select a user.');
                        return;
                    }

                    fetch(`{{env('APP_URL')}}/tasks/${taskId}/${action}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            discord_user_id: userId,
                            team_id: teamId,
                            item_id: item,
                            description: description
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(`Task ${action === 'complete' ? 'completed' : 'undone'} successfully!`);
                            location.reload();
                        } else {
                            alert(`Error ${action === 'complete' ? 'completing' : 'undoing'} task.`);
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
