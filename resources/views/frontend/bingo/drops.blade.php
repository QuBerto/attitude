@if($bingoCard->teams)
<h2 class="font-semibold text-xl leading-tight rs-yellow">
    {{ __('Completions') }}
</h2>
<div class="overflow-x-auto">
  <table class="min-w-full divide-y divide-gray-300">
      <thead>
        <tr>
          <th scope="col" colspan="2" class="py-3.5 pl-4 pr-3 text-left font-semibold sm:pl-0 rs-yellow">Tile</th>
          <th scope="col" class="px-3 py-3.5 text-left font-semibold rs-yellow">Item</th>
          <th scope="col" class="px-3 py-3.5 text-left font-semibold rs-yellow">User</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-800">
        @isset($team)
        @foreach($team->completions as $completion)
        <tr>
          <td class="whitespace-nowrap px-3 py-4 text-white">
            <div class="flex justify-center">
              <img class="max-h-12 w-auto" src="{{$completion->task->tile->getFirstMediaUrl('*')}}" alt="{{ $completion->task->tile->title }}">
            </div>
          </td>
          <td class="whitespace-nowrap px-3 py-4 text-white">{{ $completion->task->description }}<br>
            <small>{{ $completion->task->tile->title }}</small>
          </td>
          <td class="whitespace-nowrap px-3 py-4 text-white">{{ $completion->description }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-white">{{ $completion->user->nick }}</td>
        </tr>
        @endforeach
        @else
        @foreach($bingoCard->teams[0]->completions as $completion)
        <tr>
          <td class="whitespace-nowrap px-3 py-4 text-white">
            {{ $completion->task->description }}
          </td>
          <td class="whitespace-nowrap px-3 py-4 text-white">{{ $completion->description }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-white">{{ $completion->user->username }}</td>
        </tr>
        @endforeach
        @endisset
      </tbody>
    </table>
</div>
@endif
