<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    /**
     * List of available events
     */
    public function index()
    {
        $events = Event::inAppItems()->paginate(5);

        return $this->outPaginated(EventResource::collection($events));
    }

    /**
     * Show particular event
     */
    public function show(Event $event)
    {
        if ($event->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'),403);
        }

        return $this->out(new EventResource($event));
    }
}
