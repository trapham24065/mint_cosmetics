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
use App\Services\Storefront\GhnService;

use App\Services\Storefront\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{

    /**
     * The cart service instance.
     */
    protected CartService $cartService;

    protected GhnService $ghnService;

    /**
     * Create a new controller instance.
     */
    public function __construct(CartService $cartService, GhnService $ghnService)
    {
        $this->cartService = $cartService;
        $this->ghnService = $ghnService;
    }

    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        // Get the contents of the cart from the service
        $cartData = $this->cartService->getCartContents();

        // If the cart is empty, redirect the user back to the shop page
        if (empty($cartData['items'])) {
            return redirect()->route('shop')->with('info', 'Your cart is empty. Please add products to proceed.');
        }

        // Otherwise, show the checkout page with the cart data
        return view('storefront.checkout', array_merge($cartData, [
            'shipping_fee' => 0,
            'grand_total' => $cartData['total'],
        ]));
    }

    public function provinces(): JsonResponse
    {
        return response()->json($this->ghnService->provinces());
    }

    public function districts(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'province_id' => ['required', 'integer'],
        ]);

        return response()->json($this->ghnService->districts((int) $validated['province_id']));
    }

    public function wards(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'district_id' => ['required', 'integer'],
        ]);

        return response()->json($this->ghnService->wards((int) $validated['district_id']));
    }

    public function fee(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'district_id' => ['required', 'integer'],
            'ward_code' => ['required', 'string'],
        ]);

        $cartData = $this->cartService->getCartContents();
        $weightGram = $this->ghnService->estimateWeight($cartData['items']);
        $feeData = $this->ghnService->calculateFee((int) $validated['district_id'], (string) $validated['ward_code'], $weightGram);
        $shippingFee = (float) ($feeData['total'] ?? 0);

        return response()->json([
            'shipping_fee' => $shippingFee,
            'grand_total' => (float) $cartData['total'] + $shippingFee,
            'raw' => $feeData,
        ]);
    }
}
