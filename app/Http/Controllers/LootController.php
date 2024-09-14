<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LootController extends Controller
{
    public function store(Request $request)
    {
        // Log the full incoming request
        Log::info('Incoming request:', [
            'headers' => $request->headers->all(),  // Log headers
            'body' => $request->all(),              // Log the request body
            'ip' => $request->ip(),                 // Log the client's IP address
            'method' => $request->method(),         // Log the HTTP method (e.g., POST)
            'url' => $request->fullUrl(),           // Log the full URL of the request
        ]);

        // You can also log specific request parameters like this:
        // Log::info('Specific parameter:', ['param_name' => $request->input('param_name')]);

        // Proceed with your logic here
        // return response()->json([...]);
    }
}
