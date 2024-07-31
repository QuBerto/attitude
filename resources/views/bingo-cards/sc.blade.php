<form action="{{ route('capture.screenshot') }}" method="POST" class="border shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @csrf
    <div class="mb-4">
        <label for="team" class="block text-sm font-medium mb-2">Team:</label>
        <select id="team" name="team" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @foreach ($bingoCard->teams as $bingoteam)
                <option value="{{ $bingoteam['id'] }}">{{ $bingoteam['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="channel" class="block text-sm font-medium mb-2">Channel:</label>
        <select id="channel" name="channel" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @foreach ($channels as $channel)
                @isset($channel['name'])
                <option value="{{ $channel['id'] }}">{{ $channel['name'] }}</option>
                @endisset
            @endforeach
        </select>
    </div>
    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Capture Screenshot</button>
    </div>
</form>
