<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/11/2025
 * @time 11:11 PM
 */

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\Storefront\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{

    protected WishlistService $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index(): View
    {
        $products = $this->wishlistService->getContents();
        return view('storefront.product-wishlist', compact('products'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate(['product_id' => 'required|exists:products,id']);
        $result = $this->wishlistService->toggle($validated['product_id']);
        return response()->json($result);
    }

    public function getIds(): JsonResponse
    {
        return response()->json($this->wishlistService->getIds());
    }

}
