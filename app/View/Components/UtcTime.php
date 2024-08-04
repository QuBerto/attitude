<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Carbon\Carbon;

class UtcTime extends Component
{
    public $currentTime;

    public function __construct()
    {
        $this->currentTime = Carbon::now('UTC')->toDateTimeString();
    }

    public function render()
    {
        return view('components.utc-time');
    }
}
