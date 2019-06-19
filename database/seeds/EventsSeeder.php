<?php

use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_1_events = factory(\App\Models\Event::class, 4)->create(
            [
                'app_id' => 1
            ]
        );

        $app_1_events = factory(\App\Models\Event::class, 5)->create(
            [
                'app_id' => 2
            ]
        );
    }
}
