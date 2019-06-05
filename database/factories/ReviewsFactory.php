<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    \App\Models\Review::class,
    function (Faker $faker) {
        return [
            'user_id' => 1,
            'stars'   => $faker->randomElement([5, 4, 3]),
            'comment' => $faker->sentence,
            'app_id'  => 1
        ];
    }
);
