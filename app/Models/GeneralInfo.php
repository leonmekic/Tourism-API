<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralInfo extends Model
{
    protected $table = 'general_info';
    protected $fillable = ['opens_at', 'closes_at', 'address', 'phone_number', 'email'];

    public function informable()
    {
        return $this->morphTo();
    }

//    public function workingHours() {
    //        return $this->hasMany('App\Models\WorkingHours', 'model_id');
    //    }
}