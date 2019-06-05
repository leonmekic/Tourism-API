<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ObjectAvgRatingResource;
use App\Http\Resources\ObjectStatisticsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Attraction;
use App\Http\Resources\AttractionsResource;
use App\Models\Review;
use Illuminate\Http\Request;

class AttractionsController extends Controller
{
    public function index()
    {
        $attractions = Attraction::with('generalInformation')->get();

        return $this->out(AttractionsResource::collection($attractions));
    }

    public function show(Attraction $attraction)
    {
        $attraction->load('generalInformation');

        return $this->out(new AttractionsResource($attraction));
    }

    public function objectReviews(Attraction $attraction)
    {
        return $this->out(ReviewsResource::collection($attraction->reviews()->get()));
    }

    public function indexReview()
    {
        $accommodations = Attraction::with('reviews')->get();

        return $this->out(ObjectAvgRatingResource::collection($accommodations));
    }

    public function showReview(Review $review)
    {
        return $this->out(new ReviewsResource($review));
    }

    public function storeReview(Attraction $attraction, ReviewStoreRequest $request)
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

        $attraction->reviews()->save($review);

        return $this->out(new ReviewsResource($review));
    }

    public function reviewStatistics(Attraction $attraction)
    {
        $attraction->number_of_reviews = $attraction->reviews()->count();
        $attraction->average_rating = $attraction->reviews()->avg('stars');

        $numbers = array(5, 4, 3, 2, 1);
        foreach ($numbers as $number) {
            $ratingCount[$number . ' stars'] = $attraction->reviews()->where('stars', $number)->count();
        }

        $attraction->rating_count = $ratingCount;

        return $this->out(new ObjectStatisticsResource($attraction));
    }
}
