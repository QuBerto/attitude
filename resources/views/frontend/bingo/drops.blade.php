@extends('layouts.frontend')
@section('content')
    <h1 class="text-3xl font-bold mb-6">Drops Index</h1>

    @if($drops->isEmpty())
        <p class="text-white">No drops found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold sm:pl-0 text-yellow-500">Player</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Event Code</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Item Source</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Items</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Value</th>
                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($drops as $drop)
                        <tr>
                            <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->player->username }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->eventcode }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->itemsource }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-white">
                                <ul class="list-disc list-inside">
                                    @foreach($drop->items as $item)
                                        <li>{{ $item['name'] }} (x{{ $item['quantity'] }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->gp }} GP</td>
                            <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@stop
