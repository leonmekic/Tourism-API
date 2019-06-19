<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(
    User::class,
    function (Faker $faker) {
        return [
            'name'                 => $faker->name,
            'email'                => $faker->unique()->safeEmail,
            'phone_number'         => $faker->phoneNumber,
            'email_verified_at'    => now(),
            'password'             => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'active'               => 1,
            'activation_token'     => '',
            'terms_and_conditions' => 1,
            'app_id'               => 1
        ];
    }
);
