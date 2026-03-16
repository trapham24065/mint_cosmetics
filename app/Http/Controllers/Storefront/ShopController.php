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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ShopController extends Controller
{

    public function index(Request $request): View
    {
        $query = Product::query()
            ->where('active', true)
            ->with(['variants', 'brand', 'category'])
            ->withCount('approvedReviews');

        // SEARCH
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // CATEGORY
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // BRAND
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // PRICE
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $min = (int)$request->min_price;
            $max = (int)$request->max_price;

            $query->whereHas('variants', function ($q) use ($min, $max) {
                $q->where(function ($sub) use ($min, $max) {
                    $sub->whereBetween('discount_price', [$min, $max])
                        ->orWhere(function ($s) use ($min, $max) {
                            $s->whereNull('discount_price')
                                ->whereBetween('price', [$min, $max]);
                        });
                });
            });
        }

        // SORT
        switch ($request->sort) {
            case 'price_asc':
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->orderByRaw('COALESCE(discount_price,price) ASC');
                break;

            case 'price_desc':
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->orderByRaw('COALESCE(discount_price,price) DESC');
                break;

            default:
                $query->latest();
        }

        $products = $query->paginate(9)->withQueryString();

        $categories = Category::where('active', true)
            ->withCount([
                'products' => function ($q) {
                    $q->where('active', true);
                },
            ])
            ->get();
        $brands = Brand::where('is_active', true)->get();

        $priceRange = DB::table('product_variants')
            ->selectRaw(
                'MIN(COALESCE(discount_price,price)) as min_price, MAX(COALESCE(discount_price,price)) as max_price'
            )
            ->first();

        return view('storefront.shop', [
            'products'   => $products,
            'categories' => $categories,
            'brands'     => $brands,
            'minPrice'   => $priceRange->min_price ?? 0,
            'maxPrice'   => $priceRange->max_price ?? 0,
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
