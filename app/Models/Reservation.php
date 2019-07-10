<?php

namespace App\Models;

use App\Contracts\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    protected $table = 'reservations';

    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }

    public function bookings()
    {
        return $this->belongsTo('App\Models\Booking');
    }
}