<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>

    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-black dark:text-white/50">

    <div class="bg-black">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]"
            src="{{ asset('storage/assets/background.svg') }}" />
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                        <span class="sr-only">Attitude OSRS</span>
                        <img class="h-8 w-auto" src="{{ asset('storage/assets/logo.png') }}" alt="">
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button type="button"
                        class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                        id="menu_open">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="{{ url('/calendar') }}" class="text-sm font-semibold leading-6 text-gray-100">Events</a>
                    <a href="{{ url('/members') }}" class="text-sm font-semibold leading-6 text-gray-100">Members</a>
                    <a href="{{ url('/golden-spoon') }}" class="text-sm font-semibold leading-6 text-gray-100">Golden Spoon</a>
                    <a href="https://discord.com/invite/nXHSxhfdZr"
                        class="text-sm font-semibold leading-6 text-gray-100">Discord</a>
                </div>


                @auth
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 text-gray-100">Dashboard
                            <span aria-hidden="true">&rarr;</span></a>
                    </div>
                @else
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-100">Log in <span
                                aria-hidden="true">&rarr;</span></a>
                    </div>
                @endauth




            </nav>
            <!-- Mobile menu, show/hide based on menu open state. -->
            <div class="lg:hidden hidden" role="dialog" aria-modal="true">
                <!-- Background backdrop, show/hide based on slide-over state. -->
                <div class="fixed inset-0 z-50"></div>
                <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10"
                    style="background-color: #000000dd;">
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">Attitude OSRS</span>
                            <img class="h-8 w-auto" src="{{ asset('storage/assets/logo.png') }}" alt="">
                        </a>
                        <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" id="menu_close">
                            <span class="sr-only">Close menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-2 py-6">
                                <a href="{{ url('/calendar') }}"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-100 hover:bg-gray-50 hover:text-gray-900">Events</a>
                                <a href="{{ url('/members') }}"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-100 hover:bg-gray-50 hover:text-gray-900">Members</a>
                                <a href="{{ url('/npckills') }}"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-100 hover:bg-gray-50 hover:text-gray-900">Kills</a>
                                <a href="https://discord.com/invite/nXHSxhfdZr"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-100 hover:bg-gray-50 hover:text-gray-900">Discord</a>
                            </div>
                            <div class="py-6">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-100 hover:bg-gray-50 hover:text-gray-900">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-100 hover:bg-gray-50 hover:text-gray-900">Log
                                        in</a>
                                @endauth

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    </div>

    <main>
        <div class="relative isolate">
            <svg class="absolute inset-x-0 top-0 -z-10 h-[64rem] w-full stroke-gray-200 [mask-image:radial-gradient(32rem_32rem_at_center,white,transparent)]"
                aria-hidden="true">
                <defs>
                    <pattern id="1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84" width="200" height="200" x="50%" y="-1"
                        patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none" />
                    </pattern>
                </defs>
                <svg x="50%" y="-1" class="overflow-visible fill-gray-50">
                    <path d="M-200 0h201v201h-201Z M600 0h201v201h-201Z M-400 600h201v201h-201Z M200 800h201v201h-201Z"
                        stroke-width="0" />
                </svg>
                <rect width="100%" height="100%" stroke-width="0"
                    fill="url(#1f932ae7-37de-4c0a-a8b0-a6e3b4d44b84)" />
            </svg>
            <div class="absolute left-1/2 right-0 top-0 -z-10 -ml-24 transform-gpu overflow-hidden blur-3xl lg:ml-24 xl:ml-48"
                aria-hidden="true">
                <div class="aspect-[801/1036] w-[50.0625rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"
                    style="clip-path: polygon(63.1% 29.5%, 100% 17.1%, 76.6% 3%, 48.4% 0%, 44.6% 4.7%, 54.5% 25.3%, 59.8% 49%, 55.2% 57.8%, 44.4% 57.2%, 27.8% 47.9%, 35.1% 81.5%, 0% 97.7%, 39.2% 100%, 35.2% 81.4%, 97.2% 52.8%, 63.1% 29.5%)">
                </div>
            </div>
            <div class="overflow-hidden">
                <div class="mx-auto max-w-7xl px-6 pb-32 pt-36 sm:pt-60 lg:px-8 lg:pt-32">
                    @yield('content')
                </div>
            </div>
        </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the mobile menu button and the mobile menu elements
            const menuButton = document.querySelector('#menu_open');
            const closeButton = document.querySelector('#menu_close');
            const mobileMenu = document.querySelector('div[role="dialog"]');
            const backdrop = document.querySelector('.fixed.inset-0.z-50');

            // Add event listener to open the mobile menu
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('hidden');
                backdrop.classList.remove('hidden');
            });

            // Add event listener to close the mobile menu
            closeButton.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                backdrop.classList.add('hidden');
            });

            // Close the mobile menu when the backdrop is clicked
            backdrop.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                backdrop.classList.add('hidden');
            });
        });
    </script>
</body>

</html>
