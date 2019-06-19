<?php

namespace App\Http\Controllers\Categories;

use App\Contracts\Model;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;

class CategoriesController extends Controller
{
    public function index()
    {
        if (auth()->id() != User::SuperAdminId) {
            $categoryNames = Category::whereHas(
                'app',
                function ($q) {
                    $q->where('app_id', auth()->user()->app_id);
                }
            )->pluck('name');
        } else {
            $categoryNames = Category::all()->pluck('name');
        }
        foreach ($categoryNames as $categoryName) {
            $urls[]['category'] = [
                'name' => $categoryName,
                'url'  => route($categoryName.'.list')
            ];
        }

        return $this->out($urls);
    }
}
