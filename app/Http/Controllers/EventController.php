<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|image',
        ]);

        $event = Event::create($request->all());

        if ($request->hasFile('image')) {
            $event->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('calendar.show')->with('success', 'Event created successfully.');
    }
}
