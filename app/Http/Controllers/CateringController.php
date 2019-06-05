<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ObjectAvgRatingResource;
use App\Http\Resources\ObjectStatisticsResource;
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

    public function show(Catering $catering)
    {
        $catering->load('generalInformation', 'workingHours');

        return $this->out(new CateringResource($catering));
    }

    public function objectReviews(Catering $catering)
    {
        return $this->out(ReviewsResource::collection($catering->reviews()->get()));
    }

    public function indexReview()
    {
        $accommodations = Catering::with('reviews')->get();

        return $this->out(ObjectAvgRatingResource::collection($accommodations));
    }

    public function showReview(Review $review)
    {
        return $this->out(new ReviewsResource($review));
    }

    public function storeReview(Catering $catering, Review $request)
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

        $catering->reviews()->save($review);

        return $this->out(new ReviewsResource($review));
    }

    public function reviewStatistics(Catering $catering)
    {
        $catering->number_of_reviews = $catering->reviews()->count();
        $catering->average_rating = $catering->reviews()->avg('stars');

        $numbers = array(5, 4, 3, 2, 1);
        foreach ($numbers as $number) {
            $ratingCount[$number . ' stars'] = $catering->reviews()->where('stars', $number)->count();
        }

        $catering->rating_count = $ratingCount;

        return $this->out(new ObjectStatisticsResource($catering));
    }
}
