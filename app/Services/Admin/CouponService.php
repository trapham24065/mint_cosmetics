<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/26/2025
 * @time 8:12 PM
 */

namespace App\Services\Admin;

use App\Models\Coupon;

class CouponService
{

    /**
     * Create a new coupon.
     */
    public function createCoupon(array $data): Coupon
    {
        return Coupon::create($data);
    }

    /**
     * Update an existing coupon after performing integrity checks.
     *
     * @throws \Exception
     */
    public function updateCoupon(Coupon $coupon, array $data): bool
    {
        if ($coupon->times_used > 0) {
            if ($coupon->type->value !== $data['type'] || (float)$coupon->value !== (float)$data['value']) {
                throw new \RuntimeException('Cannot change Type or Value of a coupon that has already been used.');
            }
        }

        if (isset($data['max_uses']) && $data['max_uses'] < $coupon->times_used) {
            throw new \RuntimeException(
                "Max uses cannot be less than the current number of times used ({$coupon->times_used})."
            );
        }

        return $coupon->update($data);
    }

    /**
     * Delete a coupon after checking for data integrity.
     *
     * @throws \Exception
     */
    public function deleteCoupon(Coupon $coupon): bool
    {
        if ($coupon->times_used > 0) {
            throw new \RuntimeException(
                "Cannot delete coupon '{$coupon->code}'. It has already been used {$coupon->times_used} time(s). Please deactivate it instead."
            );
        }

        return $coupon->delete();
    }

}
