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
            ->with(['variants'])
            ->withCount('approvedReviews');

        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            // Add condition to find products with category with corresponding slug
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        // Filter by price range
        $priceRange = \DB::table('product_variants')
            ->selectRaw('MIN(discount_price) as min_price, MAX(discount_price) as max_price')
            ->first();

        $minPriceFromDB = $priceRange->min_price ?? 0;
        $maxPriceFromDB = $priceRange->max_price ?? 0;
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');

            $query->whereHas('variants', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('discount_price', [$minPrice, $maxPrice]);
            });
        }

        // Filter by 'on sale'
        if ($request->input('on_sale') === 'yes') {
            $query->whereHas('variants', fn($q) => $q->whereNotNull('discount_price'));
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
            $searchTerm = '%'.$request->input('search').'%';
            $query->where('name', 'like', $searchTerm);
        }

        $products = $query->latest()->paginate(9); // Show 9 products per page

        // Get categories to display
        $categories = Category::where('active', true)->get();

        return view('storefront.shop', [
            'products'   => $products,
            'categories' => $categories,
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

            // Log for debugging
            Log::info('Quick view product found:', [
                'id'             => $product->id,
                'name'           => $product->name,
                'variants_count' => $product->variants->count(),
            ]);

            return response()->json($product);
        } catch (Exception $e) {
            Log::error('Quick view error:', [
                'product' => $product->id ?? 'unknown',
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => 'Product not found',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
