<?php
// app/Http/Controllers/Api/EventController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollection;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\CreateEvent;
use App\Http\Requests\EditEvent;
use App\Traits\HandlesEventImages;

class EventController extends Controller
{
    use HandlesEventImages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): EventCollection
    {
        $query = Event::query()->where('status', '!=', 'cancelled');

        // --- UNIVERSAL FILTERS ---
        $query->when($request->input('search'), function (Builder $query, $searchTerm) {
            $query->where(function (Builder $subQuery) use ($searchTerm) {
                $subQuery->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('location_name', 'like', "%{$searchTerm}%");
            });
        });

        $query->when($request->input('category'), function (Builder $query, $categoryKeyword) {
            $query->where('title', 'like', '%' . $categoryKeyword . '%');
        });

        // --- STATUS LOGIC ---
        $status = $request->input('status', 'upcoming');

        if ($status === 'upcoming') {
            $query->where('start_datetime', '>=', now());
        } elseif ($status === 'past') {
            $query->where('start_datetime', '<', now());
        }

        // --- DATE RANGE FILTER ---
        $query->when($request->input('start_date'), function (Builder $query, $startDate) {
            $query->whereDate('start_datetime', '>=', $startDate);
        });

        $query->when($request->input('end_date'), function (Builder $query, $endDate) {
            $query->whereDate('start_datetime', '<=', $endDate);
        });

        // --- ORDERING LOGIC ---
        $sort = $request->input('sort', null);

        if ($sort === 'created') {
            $query->orderBy('created_at', 'desc'); // latest created first
        } elseif ($sort === 'start') {
            if ($status === 'past') {
                $query->orderBy('start_datetime', 'desc'); // recent past first
            } else {
                $query->orderBy('start_datetime', 'asc'); // soonest upcoming first
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // --- PAGINATION ---
        $events = $query->paginate($request->input('per_page', 9));

        return new EventCollection($events);
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(CreateEvent $request)
    {
        $event = Event::create($request->getEventData());

        return new EventResource($event);
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        if ($event->status === 'cancelled') {
            abort(404, 'This event has been cancelled.');
        }

        return new EventResource($event);
    }

    /**
     * Update the specified event in storage.
     */
    public function update(EditEvent $request, Event $event)
    {
        $event->update($request->getUpdatedEventData($event));

        return new EventResource($event);
    }

    /**
     * Remove the specified event from storage.
     * Automatically deletes its image using the trait.
     */
    public function destroy(Event $event)
    {
        // Delete the featured image if exists
        $this->deleteEventImage($event->featured_image);

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully.']);
    }
}
