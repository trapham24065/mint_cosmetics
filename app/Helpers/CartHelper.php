<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/30/2025
 * @time 10:10 PM
 */
declare(strict_types=1);

/**
 * Get the number of products in the cart from the session.
 *
 * @return int
 */
if (!function_exists('get_cart_count')) {
    function get_cart_count(): int
    {
        return count(session()->get('cart.items', []));
    }
}
