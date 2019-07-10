<?php

namespace App\Models;

use App\Contracts\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
}
