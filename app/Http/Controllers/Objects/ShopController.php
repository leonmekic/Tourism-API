<?php

namespace App\Http\Controllers\Objects;

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

    public function index()
    {
        $shops = Shop::with('generalInformation', 'workingHours')->paginate(5);

        return $this->outPaginated(ShopResource::collection($shops));
    }

    public function show(Shop $shop)
    {
        $shop->load('generalInformation', 'workingHours');

        return $this->out(new ShopResource($shop));
    }
}
