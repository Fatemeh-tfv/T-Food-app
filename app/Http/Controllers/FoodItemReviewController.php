<?php

namespace App\Http\Controllers;

use App\Models\FoodItemReview;
use Illuminate\Http\Request;

class FoodItemReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'review' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        FoodItemReview::create([
            'food_item_id' => $request->food_item_id,
            'user_id' => auth()->user()->id,
            'review' => $request->review,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Review added successfully!');
    }

}
