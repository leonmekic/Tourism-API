<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ReviewsResource;
use App\Models\Attraction;
use App\Http\Resources\AttractionsResource;
use App\Models\Reviews;
use Illuminate\Http\Request;

class AttractionsController extends Controller
{
    public function index()
    {
        $attractions = Attraction::with('generalInformation')->get();

        return $this->out(AttractionsResource::collection($attractions));
    }

    public function show($id)
    {
        $attractions = Attraction::with('generalInformation')->find($id);

        return $this->out(new AttractionsResource($attractions));
    }

    public function storeReview(Attraction $attraction, Review $request)
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

        $attraction->reviews()->save($review);

        return $this->out(new ReviewsResource($review));
    }
}
