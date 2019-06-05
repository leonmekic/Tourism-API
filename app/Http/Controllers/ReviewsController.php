<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\ReviewsResource;
use \App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews = Review::all();

        return $this->out(ReviewsResource::collection($reviews));
    }

    public function show(Review $review)
    {
        return $this->out(new ReviewsResource($review));
    }

    public function delete(Review $review)
    {
        $review = Review::findOrFail($review->id);
        $review->delete();

        return $this->out('The review has been deleted!');
    }

    public function update(Review $review, ReviewStoreRequest $request)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json('You can only update your reviews');
        };

        $review = Review::find($review->id);

        $review->stars = $request->stars;
        $review->comment = $request->comment;
        $review->save();

        return $this->out(new ReviewsResource($review));
    }
}
