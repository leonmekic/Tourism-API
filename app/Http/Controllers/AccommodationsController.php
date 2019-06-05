<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ObjectAvgRatingResource;
use App\Http\Resources\ObjectStatisticsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Accommodation;
use App\Http\Resources\AccommodationResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccommodationsController extends Controller
{
    public function index() // List of available accommodations
    {
        $accommodations = Accommodation::with('generalInformation', 'workingHours')->get();

        return $this->out(AccommodationResource::collection($accommodations));
    }

    public function show(Accommodation $accommodation) // Show particular accommodation
    {
        $accommodation->load('generalInformation', 'workingHours');

        return $this->out(new AccommodationResource($accommodation));
    }

    public function objectReviews(Accommodation $accommodation) // Show particular accommodation reviews -- samo review se treba pokazivat
    {
        return $this->out(ReviewsResource::collection($accommodation->reviews()->get()));
    }

    public function indexReview() // List of available accommodations with review stats
    {
        $accommodations = Accommodation::with('reviews')->get();

        return $this->out(ObjectAvgRatingResource::collection($accommodations));
    }

    public function showReview(Review $review) // show particular review
    {
        return $this->out(new ReviewsResource($review));
    }

    public function storeReview(Accommodation $accommodation, ReviewStoreRequest $request)
    {
        $review = new Review(
            [
                'stars'   => $request->stars,
                'comment' => $request->comment,
                'user_id' => auth()->id(),
                'app_id'  => 1

            ]
        );

        $review->save();

        $accommodation->reviews()->save($review);

        return $this->out(new ReviewsResource($review));
    }

    public function reviewStatistics(Accommodation $accommodation) // Show particular accommodation with review statistics
    {
        $accommodation->number_of_reviews = $accommodation->reviews()->count();

        $accommodation->average_rating = $accommodation->reviews()->avg('stars');

        $numbers = array(5, 4, 3, 2, 1);
        foreach ($numbers as $number) {
            $ratingCount[$number . ' stars'] = $accommodation->reviews()->where('stars', $number)->count();
        }

        $accommodation->rating_count = $ratingCount;

        return $this->out(new ObjectStatisticsResource($accommodation));
    }
}
