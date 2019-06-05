<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    App\Models\Event::class,
    function (Faker $faker) {
        return [
            'name'   => ucfirst($faker->lastName) . "'s" . " Event",
            'type'   => $faker->randomElement([' Disco', ' Concert', ' Food Evening', ' Sailings']),
            'description' => $faker->sentence,
            'address' => $faker->address,
            'date' => $faker->dateTimeThisDecade,
            'app_id' => 1
        ];
    }
);
