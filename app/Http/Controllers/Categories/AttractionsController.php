<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Resources\ObjectAvgRatingResource;
use App\Http\Resources\ObjectStatisticsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Attraction;
use App\Http\Resources\AttractionsResource;
use App\Repositories\ReviewRepository;

class AttractionsController extends Controller
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * List of available attraction
     */
    public function index()
    {
        $attractions = Attraction::with('generalInformation')->inAppItems()->paginate(5);

        return $this->outPaginated(AttractionsResource::collection($attractions));
    }

    /**
     * Show particular accommodation
     */
    public function show(Attraction $attraction)
    {
        if ($attraction->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        $attraction->load('generalInformation', 'workingHours');

        return $this->out(new AttractionsResource($attraction));
    }

    /**
     * List of available attraction
     */
    public function objectReviews(Attraction $attraction)
    {
        if ($attraction->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

        return $this->outPaginated(ReviewsResource::collection($attraction->reviews()->with('attachments')->orderBy('created_at', 'DESC')->paginate(5)));
    }

    /**
     * List of available attraction
     */
    public function indexReview()
    {
        $attractions = Attraction::with('reviews')->inAppItems()->get();

        foreach ($attractions as $attraction) {
            $attraction->avgRating = number_format($attraction->reviews()->avg('stars'), 1);
        }

        $attractions = collect($attractions->sortByDesc('avgRating')->values()->all());
        $collection = ObjectAvgRatingResource::collection($attractions);

        return $this->paginated($collection);
    }

    /**
     * List of available attraction
     */
    public function storeReview(Attraction $attraction, ReviewCreateRequest $request)
    {
        if ($attraction->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

        $payload = [];
        $payload['stars'] = $request->input('stars');
        $payload['comment'] = $request->input('comment');

        $review = $this->reviewRepository->createReview($attraction, $payload);

        if ($request->file('photo')) {
            $review->attach($request->file('photo'), ['disk' => 'public']);
            $review->load('attachments');
        }

        return $this->out(new ReviewsResource($review), __('review.created'));
    }

    /**
     * List of available attraction
     */
    public function reviewStatistics(Attraction $attraction)
    {
        if ($attraction->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
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
