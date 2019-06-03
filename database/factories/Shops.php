<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    App\Models\Shops::class,
    function (Faker $faker) {
        return [
            'name' => ucfirst($faker->lastName) . "'s" . $faker->randomElement(
                    [' Shop', '  Store', ' Shopping mall']
                ),
            'type' => $faker->randomElement([' Shop', '  Store', ' Shopping mall']),
            'app_id' => 1
        ];
    }
);
