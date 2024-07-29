<?php

// app/Events/WordGuessed.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WordGuessed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $word;
    public $guesses;

    public function __construct($word, $guesses)
    {
        $this->word = $word;
        $this->guesses = $guesses;
    }

    public function broadcastOn()
    {
        return new Channel('word-guess');
    }
}
