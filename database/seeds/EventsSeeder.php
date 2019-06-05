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
        $app_1_events = factory(\App\Models\Event::class, 5)->create(
            [
                'app_id' => 1
            ]
        );

        foreach ($app_1_events as $event) {
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $event->workingHours()->save($workingHours);
        }

        $app_1_events = factory(\App\Models\Event::class, 5)->create(
            [
                'app_id' => 1
            ]
        );

        foreach ($app_1_events as $event) {
            $workingHours = factory(\App\Models\WorkingHours::class)->create();
            $event->workingHours()->save($workingHours);
        }
    }
}
