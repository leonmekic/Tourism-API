<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(
    App\Models\Accommodation::class,
    function (Faker $faker) {
        return [
            'name'        => ucfirst($faker->word) . $faker->randomElement([' Hotel', ' Apartment', ' Rooms']),
            'description' => $faker->sentence,
            'stars'       => mt_rand(3, 5),
            'app_id'      => 1
        ];

    }
);