<?php

namespace App\Models;

use App\Contracts\Model;
use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes, HasAttachment;

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

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
