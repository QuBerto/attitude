
@if($bingoCard->teams)
<h2 class="font-semibold mb-2 text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Completions') }}
</h2>

@foreach($bingoCard->teams[0]->completions as $completion)
<span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">
    {{ $completion }}
</span>


@endforeach

@endif