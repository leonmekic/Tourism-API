<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    App\Models\Catering::class,
    function (Faker $faker) {
        return [
            'name'   => ucfirst($faker->lastName) . "'s" . $faker->randomElement([' Service', ' Catering', ' Gastro']),
            'type'   => $faker->randomElement([' Restaurant', ' Coffee bar', ' Pizza delivery']),
            'app_id' => 1
        ];
    }
);
