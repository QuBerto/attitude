
@if($bingoCard->teams)
<div class="flex gap-4">
    @foreach($bingoCard->teams as $team)

    @php
        // Determine the class based on the iteration and active status
        $class = '';
        if ($loop->first) {
            $class = 'text-white';
        } elseif ($team->isActive) {
            $class = 'text-gray-200';
        }
    @endphp
    
    <h2 class="font-semibold mb-2 text-xl leading-tight {{ $class }}" id="{{ $team->id }}">
        {{ __($team->name) }}
    </h2>
    @endforeach
    
</div>
@foreach($bingoCard->teams[0]->users as $user)
<span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">
    {{ $user->nick }}
</span>


@endforeach

@endif