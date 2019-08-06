<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;

use App\Http\Resources\AccommodationResource;
use App\Http\Resources\RoomOccupationStatisticsResource;
use App\Http\Resources\RoomResource;
use App\Models\Accommodation;
use App\Models\Room;
use App\Models\User;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * List of available rooms for the given accommodation
     */
    public function index(Accommodation $accommodation)
    {
        $user = auth()->user();

        if ($user->id != User::SuperAdminId) {
            if ($accommodation->app_id !== $user->app_id) {
                return $this->outWithError(__('user.forbidden'), 403);
            }
            $room = $accommodation->load(
                [
                    'rooms' => function ($query) use ($user) {
                        $query->where('app_id', '=', $user->app_id);
                    }
                ]
            );
        } else {
            $room = $accommodation->load('rooms');
        }

        return $this->out(new AccommodationResource($room));
    }

    /**
     * Show particular room with booked calendar
     */
    public function show(Room $room, Request $request)
    {
        $user = auth()->user();

        if ($room->app_id !== $user->app_id && $user->id != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

        $room->calendar = $this->bookingRepository->getCalendar($room, $request->input('date'));

        return $this->out(new RoomResource($room));
    }

    /**
     * Show all bookings for particular room
     */
    public function showBookings(Room $room)
    {
        $user = auth()->user();

        if ($room->app_id !== $user->app_id && $user->id != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

        $room->load(
            [
                'bookings' => function ($query) {
                    $query->where('approved', '!=', 0);
                }
            ]
        );

        return $this->out(new RoomResource($room));
    }

    /**
     * Information about room capacity between date intervals
     */
    public function capacity(Request $request, Accommodation $accommodation)
    {
        $rooms = Room::where('accommodation_id', $accommodation->id)->whereHas('bookings')->get();

        foreach ($rooms as $room) {
            $days = $this->bookingRepository->checkBooking($room, $request->input('date'));
            $numberOfDays = count($days);
            $booked_days = array_count_values($days);

            if (array_key_exists('booked_days', $booked_days)) {
                $percent = $booked_days['booked_days'] / $numberOfDays;

                $percentage = number_format($percent * 100, 2) . '%';

            } else {
                $percentage = '0.00%';
            }

            $room->total_days = $numberOfDays;
            $room->occupation = $booked_days;
            $room->occupation_percentage = $percentage;

            $rooms_statistics[] = $room;

        }

        return $this->out(RoomOccupationStatisticsResource::collection($rooms));
    }
}
