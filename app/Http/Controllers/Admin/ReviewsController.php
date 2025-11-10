<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index()
    {
        $shop = generaleSetting('shop');
        $reviews = Review::withoutGlobalScopes()->where('shop_id', $shop?->id)->latest('id')->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleReview($reviewId)
    {
        $review = Review::withoutGlobalScopes()->find($reviewId);

        $review->update([
            'is_active' => ! $review->is_active,
        ]);

        $message = $review->is_active ? __('Review activated successfully') : __('Review deactivated successfully');

        return back()->withSuccess($message);
    }
}
