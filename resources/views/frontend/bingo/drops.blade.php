
@if($bingoCard->teams)
<h2 class="font-semibold  text-xl leading-tight rs-yellow">
    {{ __('Completions') }}
</h2>
<table class="min-w-full divide-y divide-gray-300">
    <thead>
      <tr>
        <th scope="col" class="py-3.5 pl-4 pr-3 text-left  font-semibold sm:pl-0 rs-yellow">Tile</th>
        <th scope="col" class="px-3 py-3.5 text-left  font-semibold rs-yellow">Item</th>
        <th scope="col" class="px-3 py-3.5 text-left  font-semibold rs-yellow" >User</th>
        
       
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-800">
      @isset($team)
      @foreach($team->completions as $completion)
      <tr>
        <td class="whitespace-nowrap px-3 py-4  text-white">{{ $completion->task->description }}
       
        <td class="whitespace-nowrap px-3 py-4  text-white">{{$completion->description}}</td>
 
        <td class="whitespace-nowrap px-3 py-4  text-white">{{$completion->user->nick}}</td>
      
      </tr>
      @endforeach
      @else
      @foreach($bingoCard->teams[0]->completions as $completion)
      <tr>
        <td class="whitespace-nowrap px-3 py-4  text-white">{{ $completion->task->description }}</td>
        <td class="whitespace-nowrap px-3 py-4  text-white">{{$completion->description}}</td>
        <td class="whitespace-nowrap px-3 py-4  text-white">{{$completion->user->username}}</td>
        

      
      </tr>
      @endforeach
      @endisset
      <!-- More people... -->
    </tbody>
  </table>
@endif