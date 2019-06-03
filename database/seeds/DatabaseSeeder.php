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
        // $this->call(UsersTableSeeder::class0);
        $this->call(Attractions::class);
        $this->call(Accommodation::class);
        $this->call(Catering::class);
        $this->call(Shops::class);
        //        $this->call(GeneralInfo::class);
    }
}
