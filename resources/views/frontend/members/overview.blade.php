<div class="py-12 px-4 sm:px-6 lg:px-8 w-full">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">Users</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">A list of all the users in Attitude, along with
                their stats</p>
        </div>

    </div>
    <div class="mt-8 flow-root">
        <div class="flex justify-end mb-4">
            <form method="GET" action="{{ route('frontend.members') }}">
                <input type="text" name="search" placeholder="Search by username" value="{{ request('search') }}"
                       class="border rounded-md p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <input type="hidden" name="sort_field" value="{{ request('sort_field') }}">
                <input type="hidden" name="sort_direction" value="{{ request('sort_direction') }}">
                <button type="submit" class="ml-2 px-3 py-2 bg-blue-500 dark:bg-blue-700 text-white rounded-md hover:bg-blue-600 dark:hover:bg-blue-800">Search</button>
            </form>
            
        </div>

        <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
                <table class="min-w-full border-separate border-spacing-0">
                    <thead>
                        <tr>
                            @foreach (['role' => 'Role', 'username' => 'Name', 'overall_level' => 'Total lvl', 'exp' => 'EXP', 'ehp' => 'EHP', 'ehb' => 'EHB', 'ttm' => 'TTM', 'tt200m' => 'TTM200'] as $field => $label)
                                <th scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 bg-opacity-75 py-3.5 px-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 backdrop-blur backdrop-filter">
                                    <a href="{{ route('frontend.members', array_merge(request()->all(), ['sort_field' => $field, 'sort_direction' => request('sort_direction') === 'asc' && request('sort_field') === $field ? 'desc' : 'asc'])) }}">
                                        {{ $label }}
                                        @if (request('sort_field') === $field)
                                            @if (request('sort_direction') === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </a>
                                </th>
                            @endforeach


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    @isset($ranks[$account->role])<img  class="h-4"
                                                src="{{ $ranks[$account->role]->getFirstMediaUrl('images') }}" />@endisset
                                </td>
                                <td
                                    class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 py-4 px-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                   
                                        <div class="flex items-center gap-2">
                                            @if($account->type != 'regular' && $account->type != 'unknown' )
                                            <div>
                                                @isset($ranks[$account->type])
                                                <img class="h-4"
                                                src="{{$ranks[$account->type]->getFirstMediaUrl('images') }}" />
                                                @else
                                                {{$account->type}}
                                                @endisset

                                                
                                                
                                            </div>
                                            @endif
                                            <div>
                                                {{ $account->display_name }}
                                            </div>
                                           
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{($account->overall_level)}}
                                    </td>
                                    <td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @formatNumber($account->exp)
                                    </td>
                                    <td
                                        class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ round($account->ehp) }}</td>
                                    <td
                                        class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ round($account->ehb) }}</td>
                                    <td
                                        class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ round($account->ttm) }}h</td>
                                    <td
                                        class="whitespace-nowrap border-b border-gray-200 dark:border-gray-700 px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ round($account->tt200m) }}h</td>
                                  
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
