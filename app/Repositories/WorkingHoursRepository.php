<?php

namespace App\Repositories;

use App\Contracts\Model;
use App\Contracts\Repositories\Repository;
use App\Models\WorkingHours;

class WorkingHoursRepository extends Repository {

    protected static $fields = [
        'day' => [],
        'opens_at' => [],
        'closes_at' => [],
        'model_id' => [],
        'model_type' => [],
    ];
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function getModelClass()
    {
        return WorkingHours::class;
    }

    public function createWorkingHours($model, array $payload)
    {
        $payload['model_type'] = get_class($model);
        $payload['model_id'] = $model->id;

        $generalInformation = parent::create($payload);

        return $generalInformation;
    }

    public function deleteWorkingHours(Model $model)
    {
        parent::delete($model);
    }

}