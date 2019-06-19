<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Translation;
use Faker\Generator as Faker;

$factory->define(
    Translation::class,
    function (Faker $faker) {
        return [
            'table_name' => 'news',
            'column_name'   => '',
            'foreign_key' => 1,
            'locale' => 'it',
            'value' => ''
        ];
    }
);