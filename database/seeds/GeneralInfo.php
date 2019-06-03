<?php

use Illuminate\Database\Seeder;

class GeneralInfo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accommodations = \App\Models\Accommodation::all()->pluck('id');

        factory(\App\Models\GeneralInfo::class, 10)->create(
            [
                'model_id'   => $accommodations->random(),
                'model_type' => 'App\Models\Accommodation'
            ]
        );

    }
}
