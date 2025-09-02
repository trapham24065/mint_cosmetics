<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/1/2025
 * @time 11:03 PM
 */
declare(strict_types=1);

return [
    'vietqr' => [
        'bank_id'        => env('VIETQR_BANK_ID'),
        'account_number' => env('VIETQR_ACCOUNT_NUMBER'),
        'prefix'         => env('VIETQR_PREFIX', 'DH'),
    ],
];
