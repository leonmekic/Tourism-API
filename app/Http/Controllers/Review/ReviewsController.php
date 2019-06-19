<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewStoreRequest;
use App\Http\Resources\ReviewsResource;
use \App\Models\Review;
use App\Models\User;
use App\Repositories\ReviewRepository;

class ReviewsController extends Controller
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function delete(Review $review)
    {
        if ($review->user_id == auth()->id() or auth()->id() == User::SuperAdminId) {
            $this->reviewRepository->deleteReview($review);

            return $this->out([], __('review.deleted'));
        };

        return response()->json(__('review.private'));
    }

    public function update(Review $review, ReviewStoreRequest $request)
    {
        if ($review->user_id == auth()->id() or auth()->id() == User::SuperAdminId) {
            $payload = [];
            $payload['stars'] = $request->input('stars');
            $payload['comment'] = $request->input('comment');
            $this->reviewRepository->update($review, $payload);

            return $this->out(new ReviewsResource($review->load('attachments')), __('review.updated'));
        };

        return response()->json(__('review.private'));
    }

    public function deleteReviewPhoto(Review $review)
    {
        if ($review->user_id == auth()->id() or auth()->id() == User::SuperAdminId) {
            $review->attachments()->delete();

            return $this->out(new ReviewsResource($review), __('review.photos-deleted'));
        };

        return response()->json(__('review.private'));
    }
}
