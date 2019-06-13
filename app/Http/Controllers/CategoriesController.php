<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    protected $categoryRouteNames = [
        'accommodations.list',
        'attractions.list',
        'caterings.list',
        'shops.list',
        'events.list',
        'news.list',
    ];

    public function index()
    {
        foreach ($this->categoryRouteNames as $categoryRouteName)
        {
            $name = explode(".",$categoryRouteName);
            $urls[$name[0]] = route($categoryRouteName);
        }

        return $this->out($urls);
    }
}
