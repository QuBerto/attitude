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
                    @foreach ($teams as $team)

                        <h3 class="text-lg font-semibold mt-6">{{ $team->name }} Tasks</h3>
                        <table class="min-w-full bg-white dark:bg-gray-800 mb-6">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">{{ __('Task') }}</th>
                                    <th class="px-4 py-2">{{ __('Completed') }}</th>
                                    <th class="px-4 py-2">{{ __('User') }}</th>
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
                                            <button class="complete-task-button bg-green-500 text-white px-4 py-2 rounded" data-task-id="{{ $task->id }}" data-team-id="{{ $team->id }}">
                                                {{ __('Complete Task') }}
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
            document.querySelectorAll('.complete-task-button').forEach(button => {
                button.addEventListener('click', function () {
                    const taskId = this.dataset.taskId;
                    const teamId = this.dataset.teamId;
                    const userSelect = document.querySelector(`.user-select[data-task-id="${taskId}"][data-team-id="${teamId}"]`);
                    const userId = userSelect.value;

                    if (!userId) {
                        alert('Please select a user.');
                        return;
                    }

                    fetch(`{{env('APP_URL')}}/tasks/${taskId}/complete`, {
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
        });
    </script>
</x-app-layout>
