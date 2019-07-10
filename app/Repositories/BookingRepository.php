<?php

namespace App\Repositories;

use App\Contracts\Model;
use App\Contracts\Repositories\Repository;
use App\Models\App;
use App\Models\Booking;
use App\Models\GeneralInfo;
use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BookingRepository extends Repository
{

    protected static $fields = [
        'user_id'                => [],
        'room_id'                => [],
        'time_from'              => [],
        'time_to'                => [],
        'additional_information' => [],
        'app_id'                 => []
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function getModelClass()
    {
        return Booking::class;
    }

    public function createBooking($room, array $payload)
    {
        $startMonth = Carbon::parse($payload['time_from'], null);
        $endMonth = Carbon::parse($payload['time_to'], null);

        $requestedBookingPeriod = CarbonPeriod::create($startMonth, $endMonth)->toArray();
        $bookedPeriod = $this->getBookedDates($room);

        $overLapedDays = array_intersect($requestedBookingPeriod, $bookedPeriod);

        if (count($overLapedDays) != 0) {
            return $message = 'these dates are already booked';
        }

        $user = auth()->user();

        $payload['user_id'] = $user->id;
        $payload['room_id'] = $room->id;
        $payload['time_from'] = $startMonth;
        $payload['time_to'] = $endMonth;
        $payload['app_id'] = $user->app_id;

        $booking = parent::create($payload);

        return $booking;
    }

    public function deleteBooking(Model $model)
    {
        parent::delete($model);
    }

    public function getBookedDates(Room $room)
    {
        $booked_dates = [];
        $room->load(['bookings' => function($query){
            $query->where('approved', 1);
        } ]);

        foreach ($room->bookings as $booking) {
            $booked_dates[] = CarbonPeriod::create($booking->time_from, $booking->time_to)->toArray();
        }

        $booked_dates = array_flatten($booked_dates);

        return $booked_dates;
    }

    public function getCalendar($room, $date = null)
    {
        if (!$date) {
            $date = Carbon::now();
            $startMonth = $date->copy()->startOfMonth();
            $endMonth = $date->copy()->endOfMonth();
        } else {
            $date = Carbon::parse($date);
            $startMonth = $date->copy()->startOfMonth();
            $endMonth = $date->copy()->endOfMonth();
        }

        $booked_dates = $this->getBookedDates($room);

        $month_dates = CarbonPeriod::create($startMonth, $endMonth)->toArray();

        foreach ($month_dates as $month_date) {
            $calendar[] = [
                'date' => $month_date->format('Y-m-d'),
                'booked' => in_array($month_date, $booked_dates)
            ];
        }

        return $calendar;
    }
}