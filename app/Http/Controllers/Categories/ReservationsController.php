<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Attraction;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Store booking for a room
     */
    public function store(Attraction $attraction, Request $request)
    {
        $payload['time_from'] = $request->time_from;
        $payload['time_to'] = $request->time_to;

        $booking = $this->bookingRepository->createBooking($attraction, $payload);

        if ($booking instanceof Booking){
            return $this->out(new BookingResource($booking));
        } else {
            return $this->outWithError($booking);
        }

    }
}
