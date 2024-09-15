
@extends('layouts.frontend')
@section('content')
<main style="" class="mt-6  mx-auto">
    <div
    class="rounded-lg bg-white mb-6 p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
           
    <h2 class="font-semibold mb-2 text-xl leading-tight text-white" id="">
        Top 10
    </h2>
    @if($drops->isEmpty())
        <p>No drops found.</p>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold sm:pl-0 text-yellow-500">Player</th>
                    {{-- <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Event Code</th> --}}
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Item Source</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Items</th>
                    <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Value</th>
                    {{-- <th scope="col" class="px-3 py-3.5 text-left font-semibold text-yellow-500">Time</th> --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @foreach($drops as $drop)
                    <tr>
                    
                        <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->discordUser->username }}</td>
                        {{-- <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->eventcode }}</td> --}}
                        <td class="whitespace-nowrap px-3 py-4 text-white">
                            @php
                            $npcName = App\Enums\NpcIds::getNameById($drop->npc_id);
                            if ($npcName){
                                echo $npcName;
                            }
                            @endphp

                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-white">
                            <ul class="list-disc list-inside">
                                @foreach($drop->items as $item)
                                    @isset($item->osrsItem->name)
                                    <li>{{ $item->osrsItem->name}} {{ $item->osrsItem->value}}  (x{{ $item['quantity'] }})</li>
                                    @else
                                    
                                    <li>{{$item->item_id}} (x{{ $item['quantity'] }})</li>
                                    @endisset
                                @endforeach
                            </ul>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->ge_price }} GP</td>
                        {{-- <td class="whitespace-nowrap px-3 py-4 text-white">{{ $drop->created_at }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
<!-- Pagination links -->
<div class="pagination">
    {{ $drops->links() }}
</div>
    </div>
    @endif
    </div>
    </main>
    @stop