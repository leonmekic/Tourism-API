<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Accommodation',
            'Attraction',
            'Catering',
            'Event',
            'News',
            'Shop'
        ];

        foreach ($categories as $category) {
            factory(\App\Models\Category::class, 1)->create(
                [
                    'name' => $category
                ]
            );
        }
    }
}
