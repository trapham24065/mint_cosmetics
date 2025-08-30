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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{

    public function index(Request $request): View
    {
        // Base query for active products only
        $query = Product::query()->where('active', true)->with(['variants']);

        // Add search, filter, sort logic here if needed, similar to the admin controller

        $products = $query->latest()->paginate(9); // Show 9 products per page

        // Get categories to display
        $categories = Category::where('active', true)->get();

        return view('storefront.shop', compact('products', 'categories'));
    }

    /**
     * Fetch product data for the quick view modal.
     */
    public function quickView(Product $product): JsonResponse
    {
        // Eager-load the first variant to get price info
        $product->load(['variants.attributeValues.attribute']);

        return response()->json($product);
    }

}
