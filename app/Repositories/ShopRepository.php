<?php

namespace App\Repositories;

use App\Contracts\Repositories\Repository;
use App\Models\Shop;

class ShopRepository extends Repository {

    protected static $fields = [
        'name' => [],
        'type' => [],
        'app_id' => [],
    ];
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function getModelClass()
    {
        return Shop::class;
    }

    public function create(array $payload)
    {
        $payload['app_id'] = 2;

        $shop = parent::create($payload);

        return $shop;
    }
}