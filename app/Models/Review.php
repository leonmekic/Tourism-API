<?php

namespace App\Models;

use App\Contracts\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'stars',
        'comment',
        'user_id',
        'model_id',
        'model_type',
        'app_id'
    ];

    public function reviewable()
    {
        return $this->morphTo();
    }
}
