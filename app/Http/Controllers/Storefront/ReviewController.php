<?php

declare(strict_types=1);
namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Review;

class ReviewController extends Controller
{

    public function create(Request $request)
    {
        $token = $request->query('token');
        $orderItem = OrderItem::where('review_token', $token)->whereNull('reviewed_at')->firstOrFail();

        return view('storefront.reviews.create', compact('orderItem'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token'         => ['required', 'exists:order_items,review_token'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'review'        => ['required', 'string', 'max:1000'],
            'reviewer_name' => ['required', 'string', 'max:255'],
            'media'         => ['nullable', 'array', 'max:5'],
            'media.*'       => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $orderItem = OrderItem::where('review_token', $validated['token'])->whereNull('reviewed_at')->firstOrFail();

        $mediaPaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaPaths[] = $file->store('reviews', 'public');
            }
        }
        Review::create([
            'order_item_id' => $orderItem->id,
            'product_id'    => $orderItem->product_id,
            'review'        => $validated['review'],
            'rating'        => $validated['rating'],
            'reviewer_name' => $validated['reviewer_name'],
            'media'         => $mediaPaths,
        ]);

        $orderItem->reviewed_at = now();
        $orderItem->save();

        return redirect()->route('home')->with('success', 'Thank you for your review!');
    }

}
