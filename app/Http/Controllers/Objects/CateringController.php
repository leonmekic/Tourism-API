<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ObjectAvgRatingResource;
use App\Http\Resources\ObjectStatisticsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Catering;
use App\Http\Resources\CateringResource;
use App\Models\Review;
use App\Repositories\ReviewRepository;

class CateringController extends Controller
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * List of available caterings
     */
    public function index()
    {
        $caterings = Catering::with('generalInformation', 'workingHours')->paginate(5);

        return $this->outPaginated(CateringResource::collection($caterings));
    }

    /**
     * Show particular catering
     */
    public function show(Catering $catering)
    {
        $catering->load('generalInformation', 'workingHours');

        return $this->out(new CateringResource($catering));
    }

    /**
     * Show particular catering reviews
     */
    public function objectReviews(Catering $catering)
    {
        return $this->outPaginated(ReviewsResource::collection($catering->reviews()->paginate(5)));
    }

    /**
     * List of available caterings with review stats
     */
    public function indexReview()
    {
        $accommodations = Catering::with('reviews')->get();

        return $this->out(ObjectAvgRatingResource::collection($accommodations));
    }

    /**
     * Show particular review
     */
    public function showReview(Review $review)
    {
        return $this->out(new ReviewsResource($review));
    }

    /**
     * Store Review
     */
    public function storeReview(Catering $catering, ReviewStoreRequest $request)
    {
        $payload = [];
        $payload['stars'] = $request->input('stars');
        $payload['comment'] = $request->input('comment');

        $review = $this->reviewRepository->createReview($catering, $payload);

        if ($request->file('photo')) {
            $review->attach($request->file('photo'), ['disk' => 'public']);
            $review->load('attachments');
        }

        return $this->out(new ReviewsResource($review), __('review.created'));
    }

    /**
     * Show particular catering with review statistics
     */
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
