<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function frontendIndex()
    {
        $upcomingEvents = Event::where('status', 'upcoming')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->paginate(10);

        $pastEvents = Event::where('status', 'past') // Or just older than now
            ->orderBy('start_datetime', 'desc')
            ->take(5) // Show a few recent past events
            ->get();

        return view('events.index', compact('upcomingEvents', 'pastEvents'));
    }

    public function frontendShow(Event $event) // Route model binding by slug
    {
        if ($event->status === 'cancelled') {
            // Handle cancelled events, maybe show a message
        }
        // You might want to restrict access to past events or show them differently

        return view('events.show', compact('event'));
    }
}