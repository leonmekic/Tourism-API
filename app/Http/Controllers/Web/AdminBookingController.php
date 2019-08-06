<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Http\Resources\AccommodationResource;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    /**
     * List of available accommodations
     */
    public function accommodationList()
    {
//        $accommodations = Accommodation::inAppItems()->get();

        $accommodations = Accommodation::all();

        //        return $this->outPaginated(AccommodationResource::collection($accommodations));
        return view('web.accommodations', compact('accommodations'));
    }

    /**
     * Show all bookings for particular room
     */
    public function showBookings(Room $room)
    {
//        $user = auth()->user();
//
//        if ($room->app_id !== $user->app_id && $user->id != User::SuperAdminId) {
//            return $this->outWithError(__('user.forbidden'), 403);
//        }

        $room->load(['bookings' => function($query){
            $query->where('approved','!=', 0);
        } ]);

//        return $this->out(new RoomResource($room));
        return view('web.bookings', compact('room'));
    }
}
