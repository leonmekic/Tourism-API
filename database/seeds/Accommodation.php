<?php

use Illuminate\Database\Seeder;

class Accommodation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_1_accommodations = factory(\App\Models\Accommodation::class, 5)->create(
            [
                'app_id' => 1
            ]
        );

        foreach ($app_1_accommodations as $accommodation) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $accommodation->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $accommodation->workingHours()->save($workingHours);
            $review = factory(\App\Models\Review::class)->create();
            $accommodation->reviews()->save($review);
        }

        $app_2_accommodations = factory(\App\Models\Accommodation::class, 5)->create(
            [
                'app_id' => 2
            ]
        );

        foreach ($app_2_accommodations as $accommodation) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $accommodation->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $accommodation->workingHours()->save($workingHours);
            $review = factory(\App\Models\Review::class)->create();
            $accommodation->reviews()->save($review);
        }

    }
}
