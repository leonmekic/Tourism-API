<?php

namespace App\Models;

use App\Contracts\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function app()
    {
        return $this->belongsToMany('App\Models\App');
    }
}