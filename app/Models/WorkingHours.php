<?php

namespace App\Models;

use App\Contracts\Model;

class WorkingHours extends Model
{
    public function workable()
    {
        return $this->morphTo();
    }
}
