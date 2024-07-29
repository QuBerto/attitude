@extends('layouts.frontend')
@section('content')


<div class="py-12 px-4 sm:px-6 lg:px-8 w-full">
    <div class="flex justify-between mb-4">
        <a href="{{ route('calendar.show', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
           class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded">Previous Month</a>
        <h3 class="text-lg font-semibold">{{ $date->format('F Y') }}</h3>
        <a href="{{ route('calendar.show', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
           class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded">Next Month</a>
    </div>
    
    <div class="lg:flex lg:h-full lg:flex-col">
        <div class="shadow ring-1 ring-black ring-opacity-5 lg:flex lg:flex-auto lg:flex-col">
            <div class="grid grid-cols-7 gap-px border-b border-gray-300 bg-gray-200 text-center text-xs font-semibold leading-6 text-gray-700 lg:flex-none">
                @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                    <div class="flex justify-center bg-white py-2">
                        <span>{{ $day }}</span>
                    </div>
                @endforeach
            </div>
            <div class="flex bg-gray-200 text-xs leading-6 text-gray-700 lg:flex-auto">
                <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
                    @foreach ($days as $day)
                        <div class="relative px-3 py-2 {{ $day->isCurrentMonth() ? 'bg-white' : 'bg-gray-50 text-gray-500' }}">
                            <time datetime="{{ $day->toDateString() }}" class="{{ $day->isToday() ? 'flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white' : '' }}">
                                {{ $day->day }}
                            </time>
                            @php
                                $events = App\Models\Event::whereDate('start_date', '<=', $day)
                                            ->whereDate('end_date', '>=', $day)
                                            ->get();
                            @endphp
                            @foreach ($events as $event)
                                <ol class="mt-2">
                                    <li>
                                        <a href="#" class="group flex">
                                            <img src="{{ $event->getFirstMediaUrl('images') }}" alt="{{ $event->name }}" class="h-6 w-6 rounded-full mr-2">
                                            <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">{{ $event->name }}</p>
                                        </a>
                                    </li>
                                </ol>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@stop