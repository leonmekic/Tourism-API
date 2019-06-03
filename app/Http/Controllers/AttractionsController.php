<?php

namespace App\Http\Controllers;

use App\Models\Attractions;
use App\Http\Resources\AttractionsResource;
use Illuminate\Http\Request;

class AttractionsController extends Controller
{
    public function index()
    {
        $attractions = Attractions::with('generalInformation')->get();

        return $this->out(AttractionsResource::collection($attractions));
    }

    public function show($id)
    {
        $attractions = Attractions::with('generalInformation')->find($id);

        return $this->out(new AttractionsResource($attractions));
    }
}
