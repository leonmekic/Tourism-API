<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\NewsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\News;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }
    /**
     * News list
     */
    public function index()
    {
        $shops = News::with('generalInformation', 'workingHours')->paginate(5);

        return $this->outPaginated(NewsResource::collection($shops));
    }

    /**
     * Show particular news
     */
    public function show(News $news)
    {
        $news->load('generalInformation', 'workingHours');

        return $this->out(new NewsResource($news));
    }

    public function storeReview(News $news, ReviewStoreRequest $request)
    {
        $payload = [];
        $payload['stars'] = $request->input('stars');
        $payload['comment'] = $request->input('comment');

        $review = $this->reviewRepository->createReview($news, $payload);

        if ($request->file('photo')) {
            $review->attach($request->file('photo'), ['disk' => 'public']);
            $review->load('attachments');
        }

        return $this->out(new ReviewsResource($review), __('review.created'));
    }

    public function objectReviews(News $news)
    {
        return $this->outPaginated(ReviewsResource::collection($news->reviews()->paginate(5)));
    }

    public function showReview(Review $review)
    {
        return $this->out(new ReviewsResource($review));
    }
}
