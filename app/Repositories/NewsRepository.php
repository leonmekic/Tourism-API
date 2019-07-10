<?php

namespace App\Repositories;

use App\Contracts\Repositories\Repository;
use App\Models\News;

class NewsRepository extends Repository {
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function getModelClass()
    {
        return News::class;
    }
}