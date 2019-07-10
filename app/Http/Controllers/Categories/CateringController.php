<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Resources\ObjectAvgRatingResource;
use App\Http\Resources\ObjectStatisticsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Catering;
use App\Http\Resources\CateringResource;
use App\Models\User;
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
        $caterings = Catering::with('generalInformation', 'workingHours')->inAppItems()->paginate(5);

        return $this->outPaginated(CateringResource::collection($caterings));
    }

    /**
     * Show particular catering
     */
    public function show(Catering $catering)
    {
        if ($catering->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        $catering->load('generalInformation', 'workingHours');

        return $this->out(new CateringResource($catering));
    }

    /**
     * Show particular catering reviews
     */
    public function objectReviews(Catering $catering)
    {
        if ($catering->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'),403);
        }
        return $this->outPaginated(ReviewsResource::collection($catering->reviews()->with('attachments')->orderBy('created_at', 'DESC')->paginate(5)));
    }

    /**
     * List of available caterings with review stats
     */
    public function indexReview()
    {
        $caterings = Catering::with('reviews')->inAppItems()->get();

        foreach ($caterings as $catering) {
            $catering->avgRating = number_format($catering->reviews()->avg('stars'), 1);
        }

        $caterings = collect($caterings->sortByDesc('avgRating')->values()->all());
        $collection = ObjectAvgRatingResource::collection($caterings);

        return $this->paginated($collection);
    }

    /**
     * Store Review
     */
    public function storeReview(Catering $catering, ReviewCreateRequest $request)
    {
        if ($this->reviewRepository->userAlreadyReviewed($catering)) {
            return $this->outWithError('You have already made a review');
        }

        if ($catering->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

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
        if ($catering->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
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
