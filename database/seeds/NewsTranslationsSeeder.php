<?php

use Illuminate\Database\Seeder;

class NewsTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allNews = \App\Models\News::all();

        foreach ($allNews as $news) {
            factory(\App\Models\Translation::class)->create(
                [
                    'column_name' => 'title',
                    'foreign_key' => $news->id,
                    'value' => 'Italian ' . $news->title
                ]
            );
            factory(\App\Models\Translation::class)->create(
                [
                    'column_name' => 'body',
                    'foreign_key' => $news->id,
                    'value' => 'Italian ' . $news->body
                ]
            );
        }
    }
}
