<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Room;
use App\Repositories\BookingRepository;

class BookingsController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Store booking for a room
     */
    public function store(Room $room, StoreBookingRequest $request)
    {
        $roomId = $room->id;
        $payload['time_from'] = $request->time_from;
        $payload['time_to'] = $request->time_to;
        $payload['additional_information'] = $request->additional_information;

        $booking = $this->bookingRepository->createBooking($room, $payload);

        if ($booking instanceof Booking){
            return $this->out(new BookingResource($booking));
        } else {
            return $this->outWithError($booking);
        }

    }
}
