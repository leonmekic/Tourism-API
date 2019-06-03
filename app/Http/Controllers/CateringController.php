<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ReviewsResource;
use App\Models\Catering;
use App\Http\Resources\CateringResource;
use App\Models\Review;
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

    public function storeReview(Catering $catering, Review $request)
    {
        $review = new Review(
            [
                'stars'      => $request->stars,
                'comment'    => $request->comment,
                'user_id'    => auth()->id(),
                'app_id'     => 1

            ]
        );

        $review->save();

        $catering->reviews()->save($review);

        return $this->out(new ReviewsResource($review));
    }
}
