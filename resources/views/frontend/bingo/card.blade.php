
@php
if(isset($team)){
    echo '<h2 style="text-align:center; font-size:60px!important; line-heigth:100px!important; " class="bingotile w-full">'.$team->name.'</h2>';
}
@endphp
<div class="grid  gap-4 w-full cards-grid">
 
    @foreach($bingoCard->tiles as $tile)
        @php
        $classes = '';

        if(isset($team) && $team->hasCompletedAllTasks($tile)){
            $classes = 'completed ';
        }
         $style = ''; 
        if(!$bingoglobal){
            $style = ' box-shadow: inset 0 0 0 2000px rgba(0, 0, 0, 0.5);';
        }
        else{
            $classes .= ' bg-black ';
        }
        @endphp 
        <div style=" aspect-ratio: 1/1;background-image:url({{ $tile->getFirstMediaUrl("*") }});{{$style}}" class="bingo-tile-tile {{$classes}}bg-contain bg-no-repeat bg-center tile-container flex flex-col h-full rounded-lg flex justify-center align-center bg-stone-900 p-2 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20] bingotile"> <!-- Fixed height and width for each tile -->
            
            @include('frontend.bingo.tile', ['tile' => $tile])
        </div>
    @endforeach
</div>
<div>
@if(!$bingoglobal)
@foreach($team->users as $user)
<span class="inline-block mb-2 bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-300 mr-2">
    {{ $user->nick }}
</span>
@endforeach
@endif
</div>

<style>
.cards-grid {
  grid-template-columns: repeat(5, minmax(0, 1fr));
}
@media only screen and (max-width: 600px) {
    .cards-grid {
  grid-template-columns: repeat(2, minmax(0, 1fr));
}
}

</style>