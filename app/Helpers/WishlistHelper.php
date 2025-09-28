<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/12/2025
 * @time 12:09 AM
 */
if (!function_exists('get_wishlist_count')) {
    /**
     * Get the number of products in the wishlist from session.
     *
     * @return int
     */
    function get_wishlist_count(): int
    {
        if (app()->bound('session')) {
            return count(session()->get('wishlist', []));
        }
        return 0;
    }
}
