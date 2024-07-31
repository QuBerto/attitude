@extends('layouts.frontend')
@section('content')



                <main class="mt-6">
                    <div class="grid gap-6 mb-6">
                    <div id="docs-card"
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                            @include('frontend.bingo.card')
                        </div>
                    </div>
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        
                        <div 
                            class="rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                            @include('frontend.bingo.teams')
                        </div>

                        <div 
                            class="rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                            @include('frontend.bingo.drops')
                        </div>

                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 bg-zinc-900 ring-zinc-800 hover:text-white/70 hover:ring-zinc-700 focus-visible:ring-[#FF2D20]">
                            @include('frontend.bingo.controls')
                        </div>
                    </div>
                </main>

                @stop
     