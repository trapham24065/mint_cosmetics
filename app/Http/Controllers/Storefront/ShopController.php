<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/25/2025
 * @time 3:16 PM
 */

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ShopController extends Controller
{

    public function index(Request $request): View
    {
        // Base query for active products only
        $query = Product::query()
            ->where('active', true)
            ->with(['variants', 'brand'])            // eager‑load brand for display/filter
            ->withCount('approvedReviews');

        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            // Add condition to find products with category with corresponding slug
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // brand filter if provided
        if ($request->filled('brand')) {
            $brandSlug = $request->input('brand');
            $query->whereHas('brand', fn($q) => $q->where('slug', $brandSlug));
        }
        // Filter by price range
        $priceRange = \DB::table('product_variants')
            ->selectRaw('MIN(COALESCE(discount_price, price)) as min_price, MAX(COALESCE(discount_price, price)) as max_price')
            ->first();

        $minPriceFromDB = $priceRange->min_price ?? 0;
        $maxPriceFromDB = $priceRange->max_price ?? 0;
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = (int) $request->input('min_price');
            $maxPrice = (int) $request->input('max_price');

            $query->whereHas('variants', function ($q) use ($minPrice, $maxPrice) {
                $q->where(function ($subQ) use ($minPrice, $maxPrice) {
                    $subQ->whereBetween('discount_price', [$minPrice, $maxPrice])
                        ->orWhere(function ($subQ2) use ($minPrice, $maxPrice) {
                            $subQ2->whereNull('discount_price')
                                ->whereBetween('price', [$minPrice, $maxPrice]);
                        });
                });
            });
        }

        // Filter by 'on sale'
        if ($request->input('on_sale') === 'yes') {
            $query->whereHas('variants', fn($q) => $q->whereNotNull('discount_price')->where('discount_price', '<', \DB::raw('price')));
        }

        // Sort products
        if ($request->filled('sort')) {
            switch ($request->input('sort')) {
                case 'trending': // Newest
                    $query->latest();
                    break;
                case 'best-selling':
                    // You would need a sales_count column for this to work properly
                    // $query->orderByDesc('sales_count');
                    break;
            }
        } else {
            $query->latest(); // Default sort by newest
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where('name', 'like', $searchTerm);
        }

        $products = $query->latest()->paginate(9); // Show 9 products per page

        // Get categories to display
        $categories = Category::where('active', true)->get();
        $brands     = Brand::where('is_active', true)->get();

        return view('storefront.shop', [
            'products'   => $products,
            'categories' => $categories,
            'brands'     => $brands,
            'minPrice'   => $minPriceFromDB,
            'maxPrice'   => $maxPriceFromDB,
        ]);
    }

    /**
     * Fetch product data for the quick view modal.
     */
    public function quickView(Product $product): JsonResponse
    {
        try {
            // Eager-load the first variant to get price info
            $product->load(['variants.attributeValues.attribute', 'category', 'brand']);
            $product->loadCount('reviews');
            $product->loadAvg('reviews', 'rating');
            $data = $product->toArray();

            $data['rating'] = $product->reviews_avg_rating ?? 0;

            return response()->json($data);
        } catch (Exception $e) {
            Log::error('Quick view error:', [
                'product' => $product->id ?? 'unknown',
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => 'Product not found',
                'message' => 'Unable to load product details.',
            ], 500);
        }
    }
}
