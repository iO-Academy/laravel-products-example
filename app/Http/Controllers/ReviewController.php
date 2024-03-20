<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        // When creating with a one to many
        // We just take a relationship id (product_id) in the reqest data
        // and save it onto the model
        $request->validate([
            'name' => 'required|string|max:30',
            'review' => 'required|string|max:500',
            // We can use exists:table,field to validate that given
            // ids actually exist in our database
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $review = new Review();
        $review->name = $request->name;
        $review->review = $request->review;
        $review->product_id = $request->product_id;

        if (! $review->save()) {
            return response()->json([
                'message' => 'Review did not save sorry'
            ], 500);
        }

        return response()->json([
            'message' => 'Review created'
        ], 201);
    }
}
