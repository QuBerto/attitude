
@if($bingoCard->teams)
<h2 class="font-semibold mb-2 text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Completions') }}
</h2>
<table class="min-w-full divide-y divide-gray-300">
    <thead>
      <tr>
        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-300 sm:pl-0">Tile</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300" >User</th>
        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-300">Team</th>
       
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      @isset($team)
      @foreach($team->completions as $completion)
      <tr>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $completion->task->description }}@if($completion->description)
          <br>
          <small>{{$completion->description}}</small>
          @endif
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{$completion->user->nick}}</td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{$completion->team->name}}</td>

      
      </tr>
      @endforeach
      @else
      @foreach($bingoCard->teams[0]->completions as $completion)
      <tr>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $completion->task->description }}</td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{$completion->user->username}}</td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">{{$completion->team->name}}</td>

      
      </tr>
      @endforeach
      @endisset
      <!-- More people... -->
    </tbody>
  </table>
@endif