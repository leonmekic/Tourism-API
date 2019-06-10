<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UsersTableSeeder::class);
        $this->call(Attractions::class);
        $this->call(Accommodation::class);
        $this->call(Catering::class);
        $this->call(Shop::class);
        $this->call(EventsSeeder::class);
        $this->call(GeneralInfo::class);
    }
}
