<!-- resources/views/drops/index.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Drops Index</title>
</head>
<body>
    <h1>Drops Index</h1>

    @if($drops->isEmpty())
        <p>No drops found.</p>
    @else
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Player</th>
                    <th>Event Code</th>
                    <th>Item Source</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drops as $drop)
                    <tr>
                        <td>{{ $drop->player->username }}</td>
                        <td>{{ $drop->eventcode }}</td>
                        <td>{{ $drop->itemsource }}</td>
                        <td>
                            <ul>
                                @foreach($drop->items as $item)
                                    <li>{{ $item['name'] }} (x{{ $item['quantity'] }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $drop->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
