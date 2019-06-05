<?php

use Illuminate\Database\Seeder;

class Shop extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_1_shops = factory(\App\Models\Shop::class, 5)->create(
            [
                'app_id' => 1
            ]
        );

        foreach ($app_1_shops as $shop) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $shop->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $shop->workingHours()->save($workingHours);
        }

        $app_2_shops = factory(\App\Models\Shop::class, 5)->create(
            [
                'app_id' => 2
            ]
        );

        foreach ($app_2_shops as $shop) {
            $general_info = factory(\App\Models\GeneralInfo::class)->create();
            $shop->generalInformation()->save($general_info);
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $shop->workingHours()->save($workingHours);
        }
    }
}
