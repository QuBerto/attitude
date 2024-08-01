
@if($bingoCard->teams)
<h2 class="font-semibold  text-xl leading-tight bingotile">
    {{ __('Completions') }}
</h2>
<table class="min-w-full divide-y divide-gray-300">
    <thead>
      <tr>
        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-0 bingotile">Tile</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold bingotile" >User</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold bingotile">Team</th>
       
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      @isset($team)
      @foreach($team->completions as $completion)
      <tr>
        <td class="whitespace-nowrap px-3 py-4 text-sm bingotile">{{ $completion->task->description }}@if($completion->description)
          <br>
          <small>{{$completion->description}}</small>
          @endif
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm bingotile">{{$completion->user->nick}}</td>
        <td class="whitespace-nowrap px-3 py-4 text-sm bingotile">{{$completion->team->name}}</td>

      
      </tr>
      @endforeach
      @else
      @foreach($bingoCard->teams[0]->completions as $completion)
      <tr>
        <td class="whitespace-nowrap px-3 py-4 text-sm bingotile">{{ $completion->task->description }}</td>
        <td class="whitespace-nowrap px-3 py-4 text-sm bingotile">{{$completion->user->username}}</td>
        <td class="whitespace-nowrap px-3 py-4 text-sm bingotile">{{$completion->team->name}}</td>

      
      </tr>
      @endforeach
      @endisset
      <!-- More people... -->
    </tbody>
  </table>
@endif