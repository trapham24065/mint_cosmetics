<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/25/2025
 * @time 10:01 PM
 */

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\Storefront\CartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{

    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the cart page.
     */
    public function index(): View
    {
        $cartData = $this->cartService->getCartContents();
        return view('storefront.cart', $cartData);
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate(['updates' => ['required', 'array']]);

        try {
            $cartData = $this->cartService->updateQuantities($request->input('updates'));
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully.',
                'cart'    => $cartData,
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(int $variantId): JsonResponse
    {
        try {
            $cartData = $this->cartService->remove($variantId);
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'cart'    => $cartData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'variant_id' => ['required', 'exists:product_variants,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        try {
            $result = $this->cartService->add(
                (int)$request->input('variant_id'),
                (int)$request->input('quantity')
            );
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Get the current contents of the cart as JSON.
     */
    public function getContents(): JsonResponse
    {
        $cartData = $this->cartService->getCartContents();
        return response()->json($cartData);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate(['coupon_code' => 'required|string']);
        try {
            $cartData = $this->cartService->applyCoupon($request->input('coupon_code'));
            return response()->json(['success' => true, 'cart' => $cartData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function removeCoupon(): JsonResponse
    {
        $cartData = $this->cartService->removeCoupon();
        return response()->json(['success' => true, 'cart' => $cartData]);
    }
}
