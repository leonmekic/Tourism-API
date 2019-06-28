<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Resources\ObjectAvgRatingResource;
use App\Http\Resources\ObjectStatisticsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Accommodation;
use App\Http\Resources\AccommodationResource;
use App\Repositories\ReviewRepository;

class AccommodationsController extends Controller
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * List of available accommodations
     */
    public function index()
    {
        $accommodations = Accommodation::with('generalInformation', 'workingHours')->inAppItems()->paginate(5);

        return $this->outPaginated(AccommodationResource::collection($accommodations));
    }

    /**
     * Show particular accommodation
     */
    public function show(Accommodation $accommodation)
    {
        if ($accommodation->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        $accommodation->load('generalInformation', 'workingHours');

        return $this->out(new AccommodationResource($accommodation));

    }

    /**
     * Show particular accommodation reviews
     */
    public function objectReviews(Accommodation $accommodation)
    {
        if ($accommodation->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        return $this->outPaginated(ReviewsResource::collection($accommodation->reviews()->with('attachments')->orderBy('created_at', 'DESC')->paginate(5)));
    }

    /**
     * List of available accommodations with review stats
     */
    public function indexReview()
    {
        $accommodations = Accommodation::with('reviews')->inAppItems()->get();

        foreach ($accommodations as $accommodation) {
            $accommodation->avgRating = number_format($accommodation->reviews()->avg('stars'), 1);
        }

        $accommodations = collect($accommodations->sortByDesc('avgRating')->values()->all());
        $collection = ObjectAvgRatingResource::collection($accommodations);

        return $this->paginated($collection);
    }

    /**
     * Store Review
     */
    public function storeReview(Accommodation $accommodation, ReviewCreateRequest $request)
    {
        $payload = [];
        $payload['stars'] = $request->input('stars');
        $payload['comment'] = $request->input('comment');

        $review = $this->reviewRepository->createReview($accommodation, $payload);

        if ($request->file('photo')) {
            $review->attach($request->file('photo'), ['disk' => 'public']);
            $review->load('attachments');
        }

        return $this->out(new ReviewsResource($review), __('review.created'));
    }

    /**
     * Show particular accommodation with review statistics
     */
    public function reviewStatistics(Accommodation $accommodation)
    {
        if ($accommodation->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
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
