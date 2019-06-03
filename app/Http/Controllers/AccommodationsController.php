<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ReviewsResource;
use App\Models\Accommodation;
use App\Http\Resources\AccommodationResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccommodationsController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::with('generalInformation', 'workingHours', 'reviews')->get();

        return $this->out(AccommodationResource::collection($accommodations));
    }

    public function indexReview()
    {
        dd(1);
        $reviews = Review::where('model_type', 'App\Models\Accommodation')->get();

        return $this->out($reviews);
    }

    public function show($id)
    {
        $accommodations = Accommodation::with('generalInformation', 'workingHours')->find($id);

        return $this->out(new ReviewsResource($accommodations));
    }

    public function showReview($id)
    {
        $review = Review::where('model_type', 'App\Models\Accommodation')->find($id);

        return $this->out(new AccommodationResource($review));
    }

    public function storeReview(Accommodation $accommodation, Review $request)
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

        $accommodation->reviews()->save($review);

        return $this->out(new ReviewsResource($review));
    }
}
