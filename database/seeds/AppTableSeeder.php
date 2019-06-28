<?php

use Illuminate\Database\Seeder;

class AppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryIds = \App\Models\Category::all()->pluck('id');

        $app = factory(\App\Models\App::class)->create(
            [
                'name' => 'Tourism app'
            ]
        );
        foreach ($categoryIds as $categoryId) {
            $app->category()->attach($categoryId);
        }

        $categoryIds2 = \App\Models\Category::take(4)->pluck('id');

        $app2 = factory(\App\Models\App::class)->create(
            [
                'name' => 'Travel app'
            ]
        );
        foreach ($categoryIds2 as $categoryId) {
            $app2->category()->attach($categoryId);
        }
    }
}
