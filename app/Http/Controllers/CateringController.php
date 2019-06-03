<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Http\Resources\CateringResource;
use App\Notifications\expiredAccount;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class CateringController extends Controller
{
    public function index()
    {
        $caterings = Catering::with('generalInformation', 'workingHours')->get();

        return $this->out(CateringResource::collection($caterings));
    }

    public function show($id)
    {
        $caterings = Catering::with('generalInformation', 'workingHours')->find($id);

        return $this->out(new CateringResource($caterings));
    }
}
