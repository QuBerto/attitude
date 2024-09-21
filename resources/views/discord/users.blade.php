
  <!-- Search and Filter Form -->
  <form method="GET" action="{{ route('discord.users') }}" class="mb-4 flex items-center space-x-4">
    <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Search by username or nickname" 
        class="border rounded py-2 px-4"
    >

    <select name="role" class="border rounded py-2 px-4">
        <option value="">Filter by Role</option>
        @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">
        Filter
    </button>
</form>

<!-- Table of Discord Users -->
<table class="min-w-full divide-y divide-gray-700 bg-white dark:bg-gray-800">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="w-1/4 py-3 px-4 uppercase font-semibold text-left text-sm">Username</th>
            <th class="w-1/4 py-3 px-4 uppercase font-semibold text-left text-sm">Nick</th>

            <th class="w-1/4 py-3 px-4 uppercase font-semibold text-center text-sm">Status</th>
            {{-- <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm">RSN</th> --}}
        </tr>
    </thead>
    <tbody class="text-gray-700 dark:text-gray-300  divide-y divide-gray-800">
        @foreach ($users as $user)
            <tr>
                <td class="w-1/4 py-3 px-4">
                    <div class="flex gap-2 items-center">
                    {{ $user->username }}
                    @php
                    $roles = [];
                    @endphp
                    @foreach ($user->roles as $role)
              
                        @if($role->color === 0)
                    
                            @continue
                        @endif
                        @php
                         // Convert the decimal number to hexadecimal
                         $hex = dechex($role->color);

                        // Pad with leading zeros if necessary to ensure 6 characters
                        $color = str_pad($hex, 6, '0', STR_PAD_LEFT);

                        $roles[] = strtolower($role->name);
                        @endphp
          
                        <div style="background-color:#{{ $color }};" class="p-2 mb-1 rounded">{{ $role->name }}</div>
                    @endforeach
                    </div>
                </td>
                <td class="w-1/4 py-3 px-4">{{ $user->nick }}</td>
              
                <td class="w-1/4 py-3 px-4 text-center">
                @if($user->rsAccounts->first())
               
                  
                <div class="flex items-center justify-center gap-x-2">
                    <time class="text-gray-400 sm:hidden" datetime="2023-01-23T11:00">45 minutes ago</time>
                    <div class="flex-none rounded-full bg-green-400/10 p-1 text-green-400">
                      <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                    </div>
          
                  </div>
              
                @else
         
                <div class="flex items-center justify-center gap-x-2">
                    <time class="text-gray-400 sm:hidden" datetime="2023-01-23T00:00">12 hours ago</time>
                    <div class="flex-none rounded-full bg-rose-400/10 p-1 text-rose-400">
                      <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                    </div>
              
                  </div>
                
                @endif
            </td>
                {{-- <td class="w-1/4 py-3 px-4">
                    @foreach($user->rsAccounts as $account)
  
                        {{ $account->username }}
                        @if(in_array($account->role, $roles))
                        <div class="bg-green-700 inline">{{ $account->role }}</div>
                        @else
                        <div class="bg-red-700 rounded inline">{{ $account->role }}</div>
                        @endif
                    @endforeach
                    <form method="POST" action="{{ route('discord-users.assign-player', $user->id) }}" class="mt-2 flex items-center space-x-2">
                        @csrf
                        <input type="hidden" name="discord_user_id" value="{{ $user->id }}">
                        <select name="rs_account_id" class="block appearance-none bg-gray-800 border border-gray-700 text-white py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-gray-700 focus:border-gray-500">
                            @foreach($rsAccounts as $rsaccount)
                                <option value="{{ $rsaccount->id }}">{{ $rsaccount->username }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Submit
                        </button>
                    </form>
                </td> --}}
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination Links -->
<div class="pagination mt-4">
    {{ $users->appends(request()->query())->links() }}
</div>
