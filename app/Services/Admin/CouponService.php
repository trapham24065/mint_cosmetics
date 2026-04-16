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
                throw new \RuntimeException('Không thể thay đổi Loại hoặc Giá trị của phiếu giảm giá đã được sử dụng.');
            }
        }

        if (isset($data['max_uses']) && $data['max_uses'] < $coupon->times_used) {
            throw new \RuntimeException(
                "Số lần sử dụng tối đa không được ít hơn số lần sử dụng hiện tại ({$coupon->times_used})."
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

    /**
     * Bulk update coupons (change status).
     */
    public function bulkUpdate(string $action, array $couponIds, mixed $value): int
    {
        if ($action !== 'change_status') {
            throw new \InvalidArgumentException('Action không hợp lệ.');
        }

        $count = Coupon::whereIn('id', $couponIds)->update(['is_active' => $value]);
        return $count;
    }
}
