<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 12:25 AM
 */
declare(strict_types=1);
namespace App\Enums;

enum CouponType: string
{

    case FIXED_AMOUNT = 'fixed_amount';
    case PERCENTAGE = 'percentage';

}
