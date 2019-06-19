<?php

namespace App\Models;

use App\Contracts\Model;

class GeneralInfo extends Model
{
    protected $table = 'general_info';
    protected $fillable = ['opens_at', 'closes_at', 'address', 'phone_number', 'email'];

    public function informable()
    {
        return $this->morphTo();
    }
}
