
@if($bingoCard->teams)
<div class="flex gap-4">
    @foreach($bingoCard->teams as $bingoteam)

    @php
        // Determine the class based on the iteration and active status
        $class = '';
        if (isset($team)){
            if ($team->id == $bingoteam->id) {
                $class = 'text-white';
            }
        }
        else{
            if ($loop->first) {
            $class = '';
            } elseif ($bingoteam->isActive) {
                $class = 'text-gray-200';
            }
        }
        
    @endphp
    
    <h2 class="font-semibold mb-2 text-xl leading-tight {{ $class }}" id="{{ $bingoteam->id }}">
        <a href="{{route('frontend-teams', ['bingoCard' => $bingoCard->id, 'team' => $bingoteam->id])}}">
        {{ __($bingoteam->name) }}
        </a>
    </h2>
    @endforeach
    
</div>
@isset($team)
@foreach($team->users as $user)
<span class="inline-block mb-2 bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-300 mr-2">
    {{ $user->nick }}
</span>
@endforeach
@else
@foreach($bingoCard->teams[0]->users as $user)
<span class="inline-block mb-2 bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-300 mr-2">
    {{ $user->nick }}
</span>
@endforeach
@endisset
@endif