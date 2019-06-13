<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    \App\Models\News::class,
    function (Faker $faker) {
        return [
            'title'  => $faker->word,
            'body'   => $faker->sentence,
            'app_id' => 1
        ];
    }
);

