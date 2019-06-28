<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Resources\NewsResource;
use App\Http\Resources\ReviewsResource;
use App\Models\News;
use App\Models\Review;
use App\Repositories\ReviewRepository;

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
        $shops = News::with('attachments')->orderBy('created_at', 'DESC')->inAppItems()->paginate(5);

        return $this->outPaginated(NewsResource::collection($shops));
    }

    /**
     * Show particular news
     */
    public function show(News $news)
    {
        if ($news->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        $news->load('reviews');
        return $this->out(new NewsResource($news));
    }

    public function storeReview(News $news, ReviewCreateRequest $request)
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
        if ($news->app_id !== auth()->user()->app_id) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        return $this->outPaginated(ReviewsResource::collection($news->reviews()->with('attachments')->orderBy('created_at', 'DESC')->paginate(5)));
    }
}
