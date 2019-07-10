<?php

namespace App\Models;

use App\Contracts\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $table = 'rooms';

    public function accommodation()
    {
        return $this->belongsTo('App\Models\Accommodation');
    }

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }
}