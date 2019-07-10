<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Models\User;

class ShopController extends Controller
{
    /**
     * List of available shops
     */
    public function index()
    {
        $shops = Shop::with('generalInformation', 'workingHours')->inAppItems()->paginate(5);

        return $this->outPaginated(ShopResource::collection($shops));
    }

    /**
     * Show particular shop
     */
    public function show(Shop $shop)
    {
        if ($shop->app_id !== auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        $shop->load('generalInformation', 'workingHours');

        return $this->out(new ShopResource($shop));
    }
}
