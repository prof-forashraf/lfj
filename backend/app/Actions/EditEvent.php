<?php

namespace App\Actions;

use App\Models\Event;
use App\Traits\HandlesEventImages;
use Illuminate\Http\Request;

class EditEvent
{
    use HandlesEventImages;

    public function execute(Request $request, Event $event): Event
    {
        $event->title = $request->input('title');
        $event->description = $request->input('description');

        // Replace image if new one uploaded
        if ($request->hasFile('featured_image')) {
            // Delete old image
            $this->deleteEventImage($event->featured_image);

            // Store new image
            $event->featured_image = $this->storeEventImage($request->file('featured_image'));
        }

        $event->save();

        return $event;
    }
}
