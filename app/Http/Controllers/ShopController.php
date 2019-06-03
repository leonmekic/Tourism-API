<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShopResource;
use App\Models\Shops;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shops::with('generalInformation', 'workingHours')->get();

        return $this->out(ShopResource::collection($shops));
    }

    public function show($id)
    {
        $shops = Shops::with('generalInformation', 'workingHours')->find($id);

        return $this->out(new ShopResource($shops));
    }
}
