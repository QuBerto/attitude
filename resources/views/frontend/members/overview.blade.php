
    <div class="py-12 px-4 sm:px-6 lg:px-8 w-full">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">Users</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">A list of all the users in Attitude, along with their stats</p>
            </div>
        
        </div>
        <div class="mt-8 flow-root">
            <div class="flex justify-end mb-4">
                <form method="GET" action="{{ route('frontend.members') }}">
                    <input type="text" name="search" placeholder="Search by username" value="{{ request('search') }}" class="border rounded-md p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <button type="submit" class="ml-2 px-3 py-2 bg-blue-500 dark:bg-blue-700 text-white rounded-md hover:bg-blue-600 dark:hover:bg-blue-800">Search</button>
                </form>
            </div>
            
            <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle">
                    <table class="min-w-full border-separate border-spacing-0">
                        <thead>
                            <tr>
                                @foreach(['username' => 'Name', 'type' => 'Type', 'exp' => 'EXP', 'ttm' => 'TTM'] as $field => $label)
                                    <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 bg-opacity-75 py-3.5 px-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 backdrop-blur backdrop-filter">
                                        <a href="{{ route('frontend.members', array_merge(request()->all(), ['sort_field' => $field, 'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                            {{ $label }}
                                            @if(request('sort_field') === $field)
                                                @if(request('sort_direction') === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                @endforeach
                                <th scope="col" class="sticky top-0 z-10 border-b border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 bg-opacity-75 py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter sm:pr-6 lg:pr-8">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                                <tr>
                                    <td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 py-4 px-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{$account->username}}</td>
                                    <td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{$account->type}}</td>
                                    <td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{$account->exp}}</td>
                                    <td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{$account->ttm}}</td>
                                    <td class="relative whitespace-nowrap border-b border-gray-200 dark:border-gray-700 py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-8 lg:pr-8">
                                        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Edit<span class="sr-only">{{$account->username}}</span></a>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- More people... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $accounts->links() }}
        </div>
    </div>

