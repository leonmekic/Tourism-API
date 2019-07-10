<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewCreateRequest;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsTranslatedResource;
use App\Http\Resources\ReviewsResource;
use App\Models\News;
use App\Models\Review;
use App\Models\User;
use App\Repositories\NewsRepository;
use App\Repositories\ReviewRepository;
use Illuminate\Support\Facades\App;

class NewsController extends Controller
{
    protected $reviewRepository;
    protected $newsRepository;

    public function __construct(ReviewRepository $reviewRepository, NewsRepository $newsRepository)
    {
        $this->reviewRepository = $reviewRepository;
        $this->newsRepository = $newsRepository;
    }
    /**
     * News list
     */
    public function index($locale)
    {
        app()->setLocale($locale);

        $news = News::with('attachments')->inAppItems();

        if ($locale != 'en') {
            $news->whereHas('translations', function ($query) use ($locale) {
                $query->where('locale', $locale);
            });
        }

        return $this->outPaginated(NewsResource::collection($news->orderBy('created_at', 'DESC')->paginate(5)));
    }

    /**
     * Show particular news
     */
    public function show(News $news, $locale)
    {
        app()->setLocale($locale);

        if ($news->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

        $news->load('reviews');

        return $this->out(new NewsResource($news));
    }

    public function storeReview(News $news, ReviewCreateRequest $request)
    {
        if ($this->reviewRepository->userAlreadyReviewed($news)) {
            return $this->outWithError('You have already made a review');
        }

        if ($news->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }

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
        if ($news->app_id != auth()->user()->app_id && auth()->id() != User::SuperAdminId) {
            return $this->outWithError(__('user.forbidden'), 403);
        }
        return $this->outPaginated(ReviewsResource::collection($news->reviews()->with('attachments')->orderBy('created_at', 'DESC')->paginate(5)));
    }
}
