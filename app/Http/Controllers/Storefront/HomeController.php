<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/25/2025
 * @time 3:27 PM
 */

namespace App\Http\Controllers\Storefront;

use Illuminate\View\View;
use App\Models\Product;
use App\Models\BlogPost;

class HomeController
{

    public function index(): View
    {
        $title = "Home"; // Or your desired title

        // Fetch latest active products with their variants (needed for price/image/attributes)
        $latestProducts = Product::where('active', true)
            ->with(['variants.attributeValues']) // Eager load variants and their attribute values
            ->latest() // Order by created_at descending
            ->take(8) // Limit to 8 products
            ->get();

        // You can add more queries here for other sections like featured products, etc.
        // $featuredProducts = Product::where('is_featured', true)->...
        // --- FETCH LATEST BLOG POSTS ---
        $latestPosts = BlogPost::where('is_published', true)
            ->whereNotNull('published_at') // Ensure it has a published date
            ->orderBy('published_at', 'desc') // Order by publish date
            ->take(3) // Get the latest 3
            ->get();
        return view(
            'storefront.home',
            compact(
                'title',
                'latestProducts',
                'latestPosts'
            // Pass other variables like $featuredProducts here if needed
            )
        );
    }

}
