<?php

namespace App\Models;

use App\Contracts\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }
}