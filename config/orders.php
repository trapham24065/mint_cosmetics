<?php
/**
 * @project mint_cosmetics
 * @author M397
 * @email m397.dev@gmail.com
 * @date 1/25/2026
 * @time 10:12 PM
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Clean Pending Orders Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls whether the system should automatically cancel
    | pending orders that have exceeded the payment time limit.
    |
    | Supported: true, false
    |
    */

    'clean_pending_enabled' => env('CLEAN_PENDING_ORDERS_ENABLED', false),

];
