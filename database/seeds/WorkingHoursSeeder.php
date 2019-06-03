<?php

use Illuminate\Database\Seeder;

class WorkingHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_1_accommodations = factory(\App\Models\WorkingHours::class, 5)->create(
            [
                'app_id' => 1
            ]
        );

        foreach ($app_1_accommodations as $accommodation) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $accommodation->generalInformation()->save($general_info);
        }
    }
}
