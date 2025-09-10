<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/30/2025
 * @time 9:52 PM
 */
declare(strict_types=1);
namespace App\Services\Storefront;

use App\Enums\CouponType;
use App\Models\Coupon;
use App\Models\ProductVariant;

class CartService
{

    public function add(int $variantId, int $quantity): array
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        if ($variant->stock < $quantity) {
            throw new \RuntimeException('Product is out of stock.');
        }

        $cart = session()->get('cart.items', []);
        $cartItemId = $variant->id;

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $quantity;
        } else {
            $cart[$cartItemId] = [
                'variant_id'   => $variant->id,
                'product_id'   => $variant->product->id,
                'product_name' => $variant->product->name,
                'slug'         => $variant->product->slug,
                'variant_name' => $variant->attributeValues->map(fn($v) => $v->value)->implode(' / '),
                'quantity'     => $quantity,
                'price'        => $variant->discount_price ?? $variant->price,
                'image'        => $variant->image ?? $variant->product->image,
            ];
        }

        session()->put('cart.items', $cart);

        return [
            'success'   => true,
            'cartCount' => $this->count(),
            'addedItem' => $cart[$cartItemId],
        ];
    }

    public function getCartContents(): array
    {
        $cartItems = session()->get('cart.items', []);
        $coupon = session()->get('cart.coupon', null);
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $discountAmount = 0;
        if ($coupon) {
            $discountAmount = $this->calculateDiscount($coupon, $subtotal);
        }

        // You can add shipping logic here later
        $total = $subtotal - $discountAmount;

        return [
            'items'    => $cartItems,
            'coupon'   => $coupon,
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'total'    => $total,
        ];
    }

    public function applyCoupon(string $couponCode): array
    {
        $coupon = Coupon::where('code', $couponCode)->first();
        $cart = $this->getCartContents();

        // Kiểm tra coupon
        if (!$coupon) {
            throw new \RuntimeException('Coupon code is invalid.');
        }
        if (!$coupon->isValid()) {
            throw new \RuntimeException('This coupon is expired or no longer active.');
        }
        if ($coupon->min_purchase_amount && $cart['subtotal'] < $coupon->min_purchase_amount) {
            throw new \RuntimeException('Your cart does not meet the minimum purchase amount for this coupon.');
        }

        // Lưu coupon vào session
        session()->put('cart.coupon', $coupon);

        return $this->getCartContents();
    }

    public function removeCoupon(): array
    {
        session()->forget('cart.coupon');
        return $this->getCartContents();
    }

    /**
     * Calculate the discount amount for a given coupon and subtotal.
     *
     * @param  \App\Models\Coupon  $coupon
     * @param  float  $subtotal
     *
     * @return float
     */
    private function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        $discountValue = 0.0;

        if ($coupon->type === \App\Enums\CouponType::PERCENTAGE) {
            $discountValue = ($subtotal * (float)$coupon->value) / 100;
        } else {
            // Ensure the discount is not greater than the subtotal
            $discountValue = min((float)$coupon->value, $subtotal);
        }

        // Always return a raw float value, no formatting here.
        return $discountValue;
    }

    public function updateQuantities(array $updates): array
    {
        $cart = session()->get('cart.items', []);
        foreach ($updates as $variantId => $quantity) {
            if (isset($cart[$variantId])) {
                $variant = ProductVariant::find($variantId);
                $newQuantity = max(1, (int)$quantity); // Ensure quantity is at least 1

                if ($variant && $variant->stock < $newQuantity) {
                    throw new \RuntimeException("Not enough stock for one of the products.");
                }
                $cart[$variantId]['quantity'] = $newQuantity;
            }
        }
        session()->put('cart.items', $cart);
        return $this->getCartContents();
    }

    public function remove(int $variantId): array
    {
        session()->forget("cart.items.{$variantId}");
        return $this->getCartContents();
    }

    public function count(): int
    {
        return count(session()->get('cart', []));
    }

}
