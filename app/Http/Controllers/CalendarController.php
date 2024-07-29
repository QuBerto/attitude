<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function show(Request $request)
{
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));

    $date = Carbon::createFromDate($year, $month, 1);
    $startOfMonth = $date->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
    $endOfMonth = $date->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

    $days = [];
    for ($day = $startOfMonth->copy(); $day->lte($endOfMonth); $day->addDay()) {
        $days[] = $day->copy();
    }

    return view('frontend.events.calendar', compact('days', 'date'));
}
}
