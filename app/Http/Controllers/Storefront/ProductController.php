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
use App\Models\Category;
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
        $query = trim($request->input('query', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $matchedCategoryIds = $this->getCategoryIdsMatchingSearch($query);

        $products = Product::where('active', true)
            ->where(function ($q) use ($query, $matchedCategoryIds) {
                $q->where('name', 'like', '%' . $query . '%');
                if (!empty($matchedCategoryIds)) {
                    $q->orWhereIn('category_id', $matchedCategoryIds);
                }
            })
            ->with('variants')
            ->limit(5)
            ->get();

        return response()->json($products);
    }

    /**
     * Lấy danh sách category id (gồm cả descendant và ancestor) khớp với từ khóa.
     * Mở rộng theo cả 2 chiều để bắt được sản phẩm dù gắn ở cha hay con.
     */
    private function getCategoryIdsMatchingSearch(string $term): array
    {
        if ($term === '') {
            return [];
        }

        $allCategories = Category::query()
            ->where('active', true)
            ->get(['id', 'name', 'parent_id']);

        $byId = $allCategories->keyBy('id');
        $childrenByParent = $allCategories->groupBy(static fn(Category $c) => $c->parent_id ?? 0);

        $matchedIds = $allCategories
            ->filter(static fn(Category $c) => stripos($c->name, $term) !== false)
            ->pluck('id')
            ->all();

        $collected = [];

        // BFS xuôi để gom descendant
        $queue = $matchedIds;
        while (!empty($queue)) {
            $currentId = array_shift($queue);
            if (isset($collected[$currentId])) {
                continue;
            }
            $collected[$currentId] = true;

            foreach ($childrenByParent->get($currentId, collect()) as $child) {
                if (!isset($collected[$child->id])) {
                    $queue[] = $child->id;
                }
            }
        }

        // Đi ngược lên để gom ancestor (sản phẩm có thể được gắn ở category cha)
        foreach ($matchedIds as $matchedId) {
            $current = $byId->get($matchedId);
            while ($current && $current->parent_id) {
                if (isset($collected[$current->parent_id])) {
                    break;
                }
                $collected[$current->parent_id] = true;
                $current = $byId->get($current->parent_id);
            }
        }

        return array_keys($collected);
    }
}
