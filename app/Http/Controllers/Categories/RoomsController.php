<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;

use App\Http\Resources\AccommodationResource;
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
            $room = $accommodation->load(['rooms' => function ($query) use($user) {
                $query->where('app_id', '=', $user->app_id);
            }]);
        } else {
            $room = $accommodation->load('rooms');
        }

        return $this->out(new AccommodationResource($room));
    }

    /**
     * Show particular room
     */
    public function show(Room $room, Request $request)
    {
        $user = auth()->user();

        if ($room->app_id !== $user->app_id && $user->id != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

        $room->calendar = $this->bookingRepository->getCalendar($room, $request->input('date') );

        return $this->out(new RoomResource($room));
    }
}
