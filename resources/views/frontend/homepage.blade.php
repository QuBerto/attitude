@extends('layouts.frontend')
@section('content')
<div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center">
    <div class="relative w-full max-w-xl lg:shrink-0 xl:max-w-2xl">
      <h1 class="text-4xl font-bold tracking-tight text-gray-100 sm:text-6xl">Attitude OSRS<br>PVM/PVP Clan.</h1>
      <p class="mt-6 text-lg leading-8 text-gray-300 sm:max-w-md lg:max-w-none">Greetings from ATTITUDE, where we're not just another Old School RuneScape clan - we're a force of dedicated players aiming to make our mark on the game! Tired of the power trips and hierarchy seen elsewhere, we founded ATTITUDE to create a vibrant community where every member is valued and empowered.</p>
      <div class="mt-10 flex items-center gap-x-6">
        <a href="https://discord.com/invite/nXHSxhfdZr" class="flex items-center rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          <!-- Discord logo SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 245 240" class="w-6 h-6 mr-2" fill="#fff">
          <path d="M104.4 98.1c-5.7 0-10.4 5-10.4 11.1s4.7 11.2 10.4 11.2c5.7 0 10.4-5 10.4-11.2 0-6.1-4.7-11.1-10.4-11.1zm36.2 0c-5.7 0-10.4 5-10.4 11.1s4.7 11.2 10.4 11.2c5.7 0 10.4-5 10.4-11.2 0-6.1-4.7-11.1-10.4-11.1z"/>
          <path d="M189.5 20h-134C27.3 20 10 38.1 10 60.4v118.2c0 22.4 17.2 40.4 38.5 40.4h112.4l-5.3-18.6 12.8 11.9 12.1 11.1 21.5 18.8V60.4c0-22.3-17.2-40.4-38.5-40.4zM160 173.2s-4.4-5.3-8-10c15.9-4.5 22-14.2 22-14.2-5 3.3-9.8 5.6-14.1 7.2-6.2 2.6-12.1 4.3-17.8 5.3-11.8 2.2-22.6 1.6-31.9-.1-7.1-1.3-13.2-3.2-18.3-5.3-2.9-1.1-6.1-2.5-9.2-4.3-.4-.2-.9-.4-1.3-.6-.3-.2-.5-.3-.7-.4-.1-.1-.2-.1-.3-.2-1.3-.7-2.1-1.2-2.1-1.2s5.9 9.6 21.5 14.1c-3.6 4.6-8.1 10.2-8.1 10.2-26.7-.9-36.8-18.4-36.8-18.4 0-39 17.4-70.7 17.4-70.7C88 67.3 99.7 66.3 99.7 66.3l1.3 1.5c-12.9 3.7-18.8 9.4-18.8 9.4s1.6-.9 4.3-2.1c7.8-3.4 14-4.3 16.5-4.5.4-.1.7-.2 1.1-.2 4-.5 8.4-.7 13.1-.7 4.1.2 8.4.3 12.8.8 6 1.2 12.5 3.2 19.1 7.9 0 0-5.6-5.3-17.6-9l1.8-2c.1 0 12.1 1 23.4 9.5 0 0 17.4 31.7 17.4 70.7 0 0-10 17.5-36.7 18.4zm-63.3-34.1c-5.7 0-10.4 5-10.4 11.1s4.7 11.2 10.4 11.2c5.7 0 10.4-5 10.4-11.2 0-6.1-4.6-11.1-10.4-11.1zm36.2 0c-5.7 0-10.4 5-10.4 11.1s4.7 11.2 10.4 11.2c5.7 0 10.4-5 10.4-11.2 0-6.1-4.7-11.1-10.4-11.1z"/>
      </svg>
          Join our Discord</a>
        <a href="{{route('calendar.show')}}" class="text-sm font-semibold leading-6 text-gray-100">To events <span aria-hidden="true">→</span></a>
      </div>
    </div>
    <div class="mt-14 flex justify-end gap-8 sm:-mt-44 sm:justify-start sm:pl-20 lg:mt-0 lg:pl-0">
      <div class="ml-auto w-44 flex-none space-y-8 pt-32 sm:ml-0 sm:pt-80 lg:order-last lg:pt-36 xl:order-none xl:pt-80">
        <div class="relative">
          <img src="{{ asset('storage/assets/image_1.png') }}" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
          <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
        </div>
      </div>
      <div class="mr-auto w-44 flex-none space-y-8 sm:mr-0 sm:pt-52 lg:pt-36">
        <div class="relative">
          <img src="{{ asset('storage/assets/image_2.jpg') }}" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
          <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
        </div>
        <div class="relative">
          <img src="{{ asset('storage/assets/image_3.jpg') }}" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
          <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
        </div>
      </div>
      <div class="w-44 flex-none space-y-8 pt-32 sm:pt-0">
        <div class="relative">
          <img src="{{ asset('storage/assets/image_4.jpg') }}" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
          <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
        </div>
        <div class="relative">
          <img src="{{ asset('storage/assets/image_5.png') }}" alt="" class="aspect-[2/3] w-full rounded-xl bg-gray-900/5 object-cover shadow-lg">
          <div class="pointer-events-none absolute inset-0 rounded-xl ring-1 ring-inset ring-gray-900/10"></div>
        </div>
      </div>
    </div>
    @stop
