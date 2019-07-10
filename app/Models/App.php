<?php

namespace App\Models;

use App\Contracts\Model;

class App extends Model
{
    const AppIds = [1, 2];

    public function category()
    {
        return $this->belongsToMany('App\Models\Category');
    }
}