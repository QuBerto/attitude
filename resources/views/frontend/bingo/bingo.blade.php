@extends('layouts.frontend')
@section('content')
@php
$bingoglobal = false;
@endphp
    <main style="" class="mt-6  mx-auto">
        <div
            class="rounded-lg bg-white mb-6 p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
            @include('frontend.bingo.teams')
        </div>
        @isset($team)
            
            <div class="grid gap-6 mb-6">
                <div id="docs-card"
                    class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                    @include('frontend.bingo.card')
                    <x-utc-time />
                </div>
            </div>
            <div class="grid gap-6  lg:gap-8">



                <div id="drops-card"
                    class="rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                    @include('frontend.bingo.drops')
                    
                    <x-utc-time />
                </div>

                <div id="stats-card"
                class=" rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]"
                >
                <div class="flex items-start gap-4"
                    >
                    @include('frontend.bingo.teamstats')
                </div>
                <div class="text-gray-300 text-sm mt-6 w-full">It could take up to 6 hours after you logout before your kills will show up. To speed up this proces, update your account on the wise old mand website.</div>
                    <x-utc-time />
                
                </div>
            </div>
        @else
        <div id="docs-card" class="grid gap-6 mb-6 grid-cols-2">
            @php
            $bingoglobal = true;
            @endphp
            @foreach($bingoCard->teams as $team)
                <div 
                    class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:p-10 lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                    @include('frontend.bingo.card')
                </div>
            @endforeach
            
            <x-utc-time />
        </div>
        <div id="boss-card" class="grid gap-6 mb-6">
            <div 
                    class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:p-10 lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                  
            @include('frontend.bingo.boss')
        </div>
           
            
            <x-utc-time />
        </div>
        
        @endisset
    </main>
@stop
