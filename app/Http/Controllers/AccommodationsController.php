<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Http\Resources\AccommodationResource;
use Illuminate\Http\Request;

class AccommodationsController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::with('generalInformation', 'workingHours')->get();

        return $this->out(AccommodationResource::collection($accommodations));
    }

    public function show($id)
    {
        $accommodations = Accommodation::with('generalInformation', 'workingHours')->find($id);

        return $this->out(new AccommodationResource($accommodations));
    }
}
