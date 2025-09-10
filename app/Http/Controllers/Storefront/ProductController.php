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
use Illuminate\View\View;

class ProductController extends Controller
{

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        // Eager-load all necessary relationships for the detail view
        $product->load([
            'category',
            'brand',
            'variants.attributeValues.attribute',
            'approvedReviews',
        ]);

        // Get related products (e.g., other products in the same category)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Exclude the current product
            ->where('active', true)
            ->with('variants')
            ->withCount('approvedReviews')
            ->limit(10)
            ->get();

        return view('storefront.product-detail', compact('product', 'relatedProducts'));
    }

}
