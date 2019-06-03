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

    public function show($id)
    {
        $reviews = Review::find($id);

        return $this->out(new ReviewsResource($reviews));
    }

    //    public function create()
    //    {
    //        return view('reviews.create');
    //    }

//    public function store(Request $request)
//    {
//        $validator = Validator::make(
//            $request->all(),
//            [
//                'stars'   => 'required|integer|max:5',
//                'comment' => 'required|max:255',
//            ]
//        );
//
//        if ($validator->fails()) {
//            return response()->json($validator->errors());
//        }
//
//        $review = new Review(
//            [
//                'stars'   => $request->stars,
//                'comment' => $request->comment,
//                'user_id' => auth()->id()
//            ]
//        );
//
//        $review->save();
//    }

    //    public function edit(Review $review)
    //    {
    //        if ($review->user_id !== auth()->id()) {
    //            return response()->json('You can only edit your reviews');
    //        };
    //        $review = Review::find($review->id);
    //
    //        return view('reviews.edit', compact('review'));
    //    }

    public function delete(Review $review)
    {
        $review = Review::findOrFail($review->id);
        $review->delete();

        return $this->out('The review has been deleted!');
    }

    public function update(Review $review)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'stars'   => 'required|integer|max:5',
                'comment' => 'required|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        if ($review->user_id !== auth()->id()) {
            return response()->json('You can only update your reviews');
        };

        $review = Review::find($review->id);

        $review->stars = request()->stars;
        $review->comment = request()->comment;
        $review->save();

        return $this->out('The review has been updated!');
    }
}
