<?php

namespace App\Repositories;

use App\Contracts\Repositories\Repository;
use App\Models\Review;

class ReviewRepository extends Repository {

    protected static $fields = [
        'user_id' => [],
        'stars' => [],
        'comment' => [],
        'model_id' => [],
        'model_type' => [],
        'app_id' => [],
    ];
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function getModelClass()
    {
        return Review::class;
    }
}