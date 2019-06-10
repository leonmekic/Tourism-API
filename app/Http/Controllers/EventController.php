<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
//    private $event;
//
//    public function __construct(Event $event) {
//
//        $this->event = $event;
//    }

    public function index()
    {
        $events = Event::with('workingHours')->get();

        return $this->out(EventResource::collection($events));
    }

    public function show(Event $event)
    {
        $event->load('workingHours');

        return $this->out(new EventResource($event));
    }
}
