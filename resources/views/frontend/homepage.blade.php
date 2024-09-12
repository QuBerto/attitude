@extends('layouts.frontend')
@section('content')
<div class="mx-auto max-w-2xl gap-x-14 lg:mx-0 lg:flex lg:max-w-none lg:items-center">
    <div class="relative w-full max-w-xl lg:shrink-0 xl:max-w-2xl">
      <h1 class="text-4xl font-bold tracking-tight text-gray-100 sm:text-6xl">Attitude OSRS<br>PVM/PVP Clan.</h1>
      <p class="mt-6 text-lg leading-8 text-gray-300 sm:max-w-md lg:max-w-none">Greetings from ATTITUDE, where we're not just another Old School RuneScape clan - we're a force of dedicated players aiming to make our mark on the game! Tired of the power trips and hierarchy seen elsewhere, we founded ATTITUDE to create a vibrant community where every member is valued and empowered.</p>
      <div class="mt-10 flex items-center gap-x-6">
        <a href="https://discord.com/invite/nXHSxhfdZr" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Join our Discord</a>
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
