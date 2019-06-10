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
        $app_1_attraction = factory(\App\Models\Attraction::class, 5)->create(
            [
                'app_id' => 1
            ]
        );

        foreach ($app_1_attraction as $attraction) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $attraction->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $attraction->workingHours()->save($workingHours);
            $review = factory(\App\Models\Review::class)->create();
            $attraction->reviews()->save($review);
        }

        $app_2_attraction = factory(\App\Models\Attraction::class, 5)->create(
            [
                'app_id' => 2
            ]
        );

        foreach ($app_2_attraction as $attraction) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $attraction->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $attraction->workingHours()->save($workingHours);
            $review = factory(\App\Models\Review::class)->create();
            $attraction->reviews()->save($review);
        }
    }
}
