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
        foreach ($app_1_news as $news) {
            $review = factory(\App\Models\Review::class)->create();
            $news->reviews()->save($review);
        }
        $app_2_news = factory(\App\Models\News::class, 5)->create(
            [
                'app_id' => 2
            ]
        );
        foreach ($app_2_news as $news) {
            $review = factory(\App\Models\Review::class)->create();
            $news->reviews()->save($review);
        }
    }
}
