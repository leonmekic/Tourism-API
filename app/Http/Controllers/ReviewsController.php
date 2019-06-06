<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ReviewsResource;
use \App\Models\Review;
use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;

class ReviewsController extends Controller
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function index()
    {
        $reviews = $this->reviewRepository->all();

        return $this->out(ReviewsResource::collection($reviews));
    }

    public function show(Review $review)
    {
        return $this->out(new ReviewsResource($review));
    }

    public function delete(Review $review)
    {
        $this->reviewRepository->deleteReview($review);

        return $this->out([], __('review.deleted'));
    }

    public function update(Review $review, ReviewStoreRequest $request)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(__('review.private'));
        };

        $payload = [];
        $payload['stars'] = $request->input('stars');
        $payload['comment'] = $request->input('comment');
        $this->reviewRepository->update($review, $payload);

        return $this->out(new ReviewsResource($review), __('review.updated'));
    }

    public function upload() {
//        \Illuminate\Support\Facades\Storage::putFile('photos', new File('/Users/leon/Downloads/web-leon.jpg'));
        return 'hii';
    }
}
