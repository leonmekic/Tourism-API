<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    public function generalInformation()
    {
        return $this->morphMany('App\Models\GeneralInfo', 'informable', 'model_type', 'model_id');
    }

    public function workingHours()
    {
        return $this->morphMany('App\Models\WorkingHours', 'workable', 'model_type', 'model_id');
    }

    public function reviews()
    {
        return $this->morphMany('App\Models\Reviews', 'reviewable', 'model_type', 'model_id');
    }
}
