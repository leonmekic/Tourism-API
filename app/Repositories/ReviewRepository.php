<?php

namespace App\Repositories;

use App\Contracts\Model;
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

    public function createReview($model, array $payload)
    {
        $payload['user_id'] = auth()->id();
        $payload['model_type'] = get_class($model);
        $payload['model_id'] = $model->id;



        $review = parent::create($payload);

        return $review;
    }

    public function deleteReview(Model $model)
    {
        parent::delete($model);
    }

}