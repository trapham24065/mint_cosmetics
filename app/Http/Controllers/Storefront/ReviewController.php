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
            'review'        => ['required', 'string', 'min:10', 'max:1000'],
            'reviewer_name' => ['required', 'string', 'max:255'],
            'media'         => ['nullable', 'array', 'max:5'],
            'media.*'       => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            // token
            'token.required'         => 'Thiếu mã đánh giá.',
            'token.exists'           => 'Mã đánh giá không hợp lệ hoặc đã sử dụng.',

            // rating
            'rating.required'        => 'Vui lòng chọn số sao.',
            'rating.integer'         => 'Số sao không hợp lệ.',
            'rating.min'             => 'Số sao tối thiểu là 1.',
            'rating.max'             => 'Số sao tối đa là 5.',

            // review
            'review.required'        => 'Vui lòng nhập nội dung đánh giá.',
            'review.min'             => 'Đánh giá phải có ít nhất 10 ký tự.',
            'review.max'             => 'Đánh giá không được vượt quá 1000 ký tự.',

            // reviewer_name
            'reviewer_name.required' => 'Vui lòng nhập tên của bạn.',
            'reviewer_name.max'      => 'Tên không được vượt quá 255 ký tự.',

            // media (mảng)
            'media.array'            => 'Dữ liệu ảnh không hợp lệ.',
            'media.max'              => 'Bạn chỉ được tải tối đa 5 ảnh.',

            // media.* (từng file)
            'media.*.image'          => 'File phải là hình ảnh.',
            'media.*.mimes'          => 'Ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'media.*.max'            => 'Mỗi ảnh không được vượt quá 2MB.',
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

        return redirect()->route('home')->with('success', 'Cảm ơn bạn đã đánh giá!');
    }
}
