<?php

namespace App\Models;

use App\Contracts\Model;
use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use HasAttachment, SoftDeletes;

    protected $table = 'accommodations';

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
        return $this->morphMany('App\Models\Review', 'reviewable', 'model_type', 'model_id');
    }
}


