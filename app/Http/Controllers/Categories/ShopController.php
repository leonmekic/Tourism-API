<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Repositories\ShopRepository;

class ShopController extends Controller
{
    protected $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

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
        if ($shop->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        $shop->load('generalInformation', 'workingHours');

        return $this->out(new ShopResource($shop));
    }
}
