<?php

use Illuminate\Database\Seeder;

class Catering extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_1_catering = factory(\App\Models\Catering::class, 5, 'catering')->create(
            [
                'app_id' => 1
            ]
        );

        foreach ($app_1_catering as $catering) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $catering->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $catering->workingHours()->save($workingHours);
            $review = factory(\App\Models\Review::class)->create();
            $catering->reviews()->save($review);
        }

        $app_2_catering = factory(\App\Models\Catering::class, 5, 'catering')->create(
            [
                'app_id' => 2
            ]
        );

        foreach ($app_2_catering as $catering) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $catering->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $catering->workingHours()->save($workingHours);
            $review = factory(\App\Models\Review::class)->create();
            $catering->reviews()->save($review);
        }
    }
}
