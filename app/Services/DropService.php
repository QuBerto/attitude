<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Drop;

class DropService
{
    protected $url = 'https://example.com/api/drop'; // Replace with the actual URL

    public function sendDropData(Drop $drop)
    {
        $response = Http::post($this->url, [
            'username'   => $drop->player->username,
            'eventcode'  => $drop->eventcode,
            'itemsource' => $drop->itemsource,
            'items'      => $drop->items,
        ]);

        return $response->json();
    }
}
