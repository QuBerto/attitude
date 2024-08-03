@isset($team)

    <table class="min-w-full divide-y divide-gray-700">
        <thead>
         
        </thead>
        <tbody class="divide-y divide-gray-800">
@foreach($team->users as $user)
    @foreach($user->rsAccounts as $account)
        <tr >
            <th colspan="2" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-0 bingotile">{{$account->username}}</th>
        <tr> 
        @foreach($account->meta as $meta)

            @if ($meta->value != 0 && (strpos($meta->key, '_kills_gained') != false))
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{ucfirst(str_replace("_"," ",str_replace("_kills_gained", "", $meta->key)))}}</td>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{$meta->value}}</td>
                </tr>
            @endif
        @endforeach
        <tr><td><div style="heigth:16px;"></div></td></tr>
    @endforeach
@endforeach
</table>
@endisset