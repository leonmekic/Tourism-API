<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    \App\Models\WorkingHours::class,
    function (Faker $faker) {
        return [
            'day'        => 'All week',
            'opens_at'   => 8,
            'closes_at'  => 16,
            'model_id'   => 0,
            'model_type' => ''
        ];
    }
);
