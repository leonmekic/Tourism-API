<?php

namespace App\Listeners;

use App\Events\BookingAccepted;
use App\Jobs\SendEmailBookingAcceptedJob;
use App\Models\Room;
use App\Models\User;

class SendBookingAcceptedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function handle(BookingAccepted $event)
    {
        $user = User::find($event->booking->user_id);
        $room = Room::find($event->booking->room_id);

        $data['user_email'] = data_get($user, 'email');
        $data['room'] = data_get($room, 'room_number');
        $data['time_from'] = $event->booking->time_from;
        $data['time_to'] = $event->booking->time_to;

        SendEmailBookingAcceptedJob::dispatch($data);
    }
}
