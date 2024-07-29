<div class="mt-12 grid gap-5 sm:grid-cols-2 md:grid-cols-3">
    <!-- Total Discord Users Card -->
    <div class="relative overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-6 pt-5 shadow-sm sm:px-6 sm:pt-6">
        <div class="absolute rounded-md bg-indigo-500 p-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </div>
        <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-400">Total Discord users</p>
        <p class="ml-16 text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\DiscordUser::count() }}</p>
        <div class="absolute inset-x-0 bottom-0 bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6">
            <div class="text-sm">
                <a href="{{ route('discord-users.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View all<span class="sr-only"> Total Discord users stats</span></a>
            </div>
        </div>
    </div>

    <!-- Wise Old Man Card -->
    <div class="relative overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-6 pt-5 shadow-sm sm:px-6 sm:pt-6">
        <div class="absolute rounded-md bg-indigo-500 p-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
            </svg>
        </div>
        <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-400">Wise old man</p>
        <p class="ml-16 text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\RSAccount::count() }}</p>
        <div class="absolute inset-x-0 bottom-0 bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6">
            <div class="text-sm">
                <a href="{{ route('discord-users.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View all<span class="sr-only"> Wise Old Man stats</span></a>
            </div>
        </div>
    </div>

    <!-- Unconnected Discord Accounts Card -->
    <div class="relative overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-6 pt-5 shadow-sm sm:px-6 sm:pt-6">
        <div class="absolute rounded-md bg-indigo-500 p-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
            </svg>
        </div>
        <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-400">Unconnected discord accounts</p>
        <p class="ml-16 text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\DiscordUser::whereDoesntHave('rsAccounts')->count() }}</p>
        <div class="absolute inset-x-0 bottom-0 bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6">
            <div class="text-sm">
                <a href="{{ route('discord-users.unconnected') }}" class="font-medium text-indigo-600 hover:text-indigo-500">View all<span class="sr-only"> Unconnected Discord accounts stats</span></a>
            </div>
        </div>
    </div>
</div>