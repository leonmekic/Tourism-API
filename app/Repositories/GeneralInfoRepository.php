<?php

namespace App\Repositories;

use App\Contracts\Model;
use App\Contracts\Repositories\Repository;
use App\Models\GeneralInfo;

class GeneralInfoRepository extends Repository {

    protected static $fields = [
        'address' => [],
        'phone_number' => [],
        'email' => [],
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
        return GeneralInfo::class;
    }

    public function createGeneralInfo($model, array $payload)
    {
        $payload['model_type'] = get_class($model);
        $payload['model_id'] = $model->id;

        $generalInformation = parent::create($payload);

        return $generalInformation;
    }

    public function deleteGeneralInfo(Model $model)
    {
        parent::delete($model);
    }

}