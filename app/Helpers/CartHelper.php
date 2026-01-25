<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/30/2025
 * @time 10:10 PM
 */

declare(strict_types=1);

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

/**
 * Get the number of products in the cart (from DB if logged in, session if guest).
 *
 * @return int
 */
if (!function_exists('get_cart_count')) {
    function get_cart_count(): int
    {
        // Check if user is logged in
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            return Cart::where('customer_id', $customerId)->count();
        }

        // Guest user: get from session
        return count(session()->get('cart.items', []));
    }
}
