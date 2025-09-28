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
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{

    /**
     * The cart service instance.
     */
    protected CartService $cartService;

    /**
     * Create a new controller instance.
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
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
        return view('storefront.checkout', $cartData);
    }

}

