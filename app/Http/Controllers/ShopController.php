<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    public function index()
    {
        $shops = Shop::with('generalInformation', 'workingHours')->get();

        return $this->out(ShopResource::collection($shops));
    }

    public function show(Shop $shop)
    {
        $shop->load('generalInformation', 'workingHours');

        return $this->out(new ShopResource($shop));
    }
}
