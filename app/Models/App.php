<?php

namespace App\Models;

use App\Contracts\Model;

class App extends Model
{
    public function category()
    {
        return $this->belongsToMany('App\Models\Category');
    }
}