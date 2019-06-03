<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(
    \App\Models\GeneralInfo::class,
    function (Faker $faker) {
        return [
            'address'      => $faker->streetAddress,
            'phone_number' => $faker->phoneNumber,
            'email'        => $faker->email,
            'model_id'     => 0,
            'model_type'   => ''
        ];
    }
);
