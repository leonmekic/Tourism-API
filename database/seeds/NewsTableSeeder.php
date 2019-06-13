<?php

use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_1_news = factory(\App\Models\News::class, 5)->create(
            [
                'app_id' => 1
            ]
        );
        $app_2_news = factory(\App\Models\News::class, 5)->create(
            [
                'app_id' => 2
            ]
        );
    }
}
