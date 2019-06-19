<?php

namespace App\Models;

use App\Contracts\Model;
use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\SoftDeletes;
use TCG\Voyager\Traits\Translatable;

class News extends Model
{
    use SoftDeletes, HasAttachment, Translatable;

    protected $guarded = [];
    protected $translatable = ['title', 'body'];

    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable', 'model_type', 'model_id');
    }
}
