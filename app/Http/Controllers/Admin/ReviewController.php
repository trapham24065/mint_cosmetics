<?php

declare(strict_types=1);
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.management.reviews.index');
    }

    /**
     * Approve the specified review.
     */
    public function approve(Review $review): RedirectResponse
    {
        $review->is_approved = true;
        $review->save();
        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject the specified review (set as not approved).
     */
    public function reject(Review $review): RedirectResponse
    {
        $review->is_approved = false;
        $review->save();
        return back()->with('success', 'Review has been set to pending.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $reviews = Review::with('product')->latest()->get();

        // Format data for Grid.js
        $data = $reviews->map(function ($review) {
            return [
                'id'            => $review->id,
                'product_name'  => $review->product->name ?? 'N/A',
                'reviewer_name' => $review->reviewer_name,
                'rating'        => $review->rating,
                'review'        => \Illuminate\Support\Str::limit($review->review, 100),
                'is_approved'   => $review->is_approved,
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

}
