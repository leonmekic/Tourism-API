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

        $this->call(VoyagerDatabaseSeeder::class);
        $this->call(AppAdminRoleSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(Accommodation::class);
        $this->call(Catering::class);
        $this->call(Shop::class);
        $this->call(EventsSeeder::class);
        $this->call(Attractions::class);
        $this->call(NewsTranslationsSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(AppTableSeeder::class);

    }
}
