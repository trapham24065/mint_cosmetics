<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/30/2025
 * @time 5:23 PM
 */

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{

    /**
     * Display the specified product.
     */
    public function show(Request $request, Product $product)
    {
        // 1. Tải các mối quan hệ (BỎ 'approvedReviews' ra khỏi đây vì ta sẽ truy vấn riêng ở dưới)
        $product->load([
            'category',
            'brand',
            'images',
            'variants.attributeValues.attribute',
        ]);

        // 2. Truy vấn riêng Review và phân trang (ví dụ 5 review 1 trang)
        $reviews = $product->approvedReviews()->latest()->paginate(5);

        // 3. NẾU LÀ REQUEST AJAX (Do người dùng bấm chuyển trang review)
        if ($request->ajax()) {
            // Chỉ trả về đoạn HTML chứa danh sách review mới
            return view('storefront.partials.reviews_list', compact('reviews'))->render();
        }

        // 4. NẾU LÀ REQUEST BÌNH THƯỜNG (Load trang lần đầu)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->with('variants')
            ->withCount('approvedReviews')
            ->limit(10)
            ->get();

        return view('storefront.product-detail', compact('product', 'relatedProducts', 'reviews'));
    }

    public function searchApi(Request $request): JsonResponse
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('active', true)
            ->where('name', 'like', '%' . $query . '%')
            ->with('variants')
            ->limit(5)
            ->get();

        return response()->json($products);
    }
}
