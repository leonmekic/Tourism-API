<?php

use Illuminate\Database\Seeder;

class Attractions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Attraction::class, 5)->create(
            [
                'app_id' => 1
            ]
        );

        factory(\App\Models\Attraction::class, 5)->create(
            [
                'app_id' => 2
            ]
        );
    }
}
