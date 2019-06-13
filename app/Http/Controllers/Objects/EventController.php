<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * List of available events
     */
    public function index()
    {
        $events = Event::with('workingHours')->paginate(5);

        return $this->outPaginated(EventResource::collection($events));
    }

    /**
     * Show particular event
     */
    public function show(Event $event)
    {
        $event->load('workingHours');

        return $this->out(new EventResource($event));
    }
}
