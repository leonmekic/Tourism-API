<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    App\Models\Attraction::class,
    function (Faker $faker) {
        return [
            'name'        => ucfirst($faker->firstName) . "'s" . $faker->randomElement([' Tower', ' Castle', ' Cliff']),
            'address'     => $faker->streetAddress,
            'description' => $faker->sentence,
            'type'        => $faker->randomElement([' Tourist attraction', ' Art center', ' Sightseeing']),
            'app_id'      => 1
        ];
    }
);
