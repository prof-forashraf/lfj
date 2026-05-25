<?php

namespace App\Actions;

use App\Models\Event;
use App\Traits\HandlesEventImages;
use Illuminate\Http\Request;

class CreateEvent
{
    use HandlesEventImages;

    public function execute(Request $request): Event
    {
        $event = new Event();
        $event->title = $request->input('title');
        $event->description = $request->input('description');

        // Store image if uploaded
        if ($request->hasFile('featured_image')) {
            $event->featured_image = $this->storeEventImage($request->file('featured_image'));
        }

        $event->save();

        return $event;
    }
}
