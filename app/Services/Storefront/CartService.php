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
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartService
{

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn(): bool
    {
        return Auth::guard('customer')->check();
    }

    /**
     * Get current customer ID
     */
    protected function getCustomerId(): ?int
    {
        return Auth::guard('customer')->id();
    }

    /**
     * Add item to cart (DB if logged in, Session if guest)
     */
    public function add(int $variantId, int $quantity): array
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        if ($variant->stock < $quantity) {
            throw new \RuntimeException('Product is out of stock.');
        }

        if ($this->isLoggedIn()) {
            // Logged in: Save to database
            $this->addToDatabase($variantId, $quantity);
        } else {
            // Guest: Save to session
            $this->addToSession($variantId, $quantity, $variant);
        }

        return [
            'success'   => true,
            'cartCount' => $this->count(),
            'addedItem' => $this->formatCartItem($variant, $quantity),
        ];
    }

    /**
     * Add item to database (for logged in users)
     */
    protected function addToDatabase(int $variantId, int $quantity): void
    {
        $customerId = $this->getCustomerId();

        $cartItem = Cart::where('customer_id', $customerId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'customer_id'        => $customerId,
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
            ]);
        }
    }

    /**
     * Add item to session (for guest users)
     */
    protected function addToSession(int $variantId, int $quantity, ProductVariant $variant): void
    {
        $cart = session()->get('cart.items', []);

        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantity;
        } else {
            $cart[$variantId] = $this->formatCartItem($variant, $quantity);
        }

        session()->put('cart.items', $cart);
    }

    /**
     * Format cart item data
     */
    protected function formatCartItem(ProductVariant $variant, int $quantity): array
    {
        return [
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

    /**
     * Get cart contents (from DB if logged in, Session if guest)
     */
    public function getCartContents(): array
    {
        if ($this->isLoggedIn()) {
            $cartItems = $this->getCartFromDatabase();
        } else {
            $cartItems = session()->get('cart.items', []);
        }

        $coupon = session()->get('cart.coupon', null);
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discountAmount = 0;
        if ($coupon) {
            $discountAmount = $this->calculateDiscount($coupon, $subtotal);
        }

        $total = $subtotal - $discountAmount;

        return [
            'items'    => $cartItems,
            'coupon'   => $coupon,
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'total'    => $total,
        ];
    }

    /**
     * Get cart items from database and format them
     */
    protected function getCartFromDatabase(): array
    {
        $customerId = $this->getCustomerId();
        $cartItems = Cart::where('customer_id', $customerId)
            ->with('productVariant.product', 'productVariant.attributeValues')
            ->get();

        $formattedCart = [];
        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->productVariant;
            if ($variant && $variant->product) {
                $formattedCart[$variant->id] = [
                    'variant_id'   => $variant->id,
                    'product_id'   => $variant->product->id,
                    'product_name' => $variant->product->name,
                    'slug'         => $variant->product->slug,
                    'variant_name' => $variant->attributeValues->map(fn($v) => $v->value)->implode(' / '),
                    'quantity'     => $cartItem->quantity,
                    'price'        => $variant->discount_price ?? $variant->price,
                    'image'        => $variant->image ?? $variant->product->image,
                ];
            }
        }

        return $formattedCart;
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

    /**
     * Update cart item quantities
     */
    public function updateQuantities(array $updates): array
    {
        if ($this->isLoggedIn()) {
            $this->updateQuantitiesInDatabase($updates);
        } else {
            $this->updateQuantitiesInSession($updates);
        }

        return $this->getCartContents();
    }

    /**
     * Update quantities in database (for logged in users)
     */
    protected function updateQuantitiesInDatabase(array $updates): void
    {
        $customerId = $this->getCustomerId();

        foreach ($updates as $variantId => $quantity) {
            $variant = ProductVariant::find($variantId);
            $newQuantity = max(1, (int)$quantity);

            if ($variant && $variant->stock < $newQuantity) {
                throw new \RuntimeException("Not enough stock for one of the products.");
            }

            $cartItem = Cart::where('customer_id', $customerId)
                ->where('product_variant_id', $variantId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            }
        }
    }

    /**
     * Update quantities in session (for guest users)
     */
    protected function updateQuantitiesInSession(array $updates): void
    {
        $cart = session()->get('cart.items', []);

        foreach ($updates as $variantId => $quantity) {
            if (isset($cart[$variantId])) {
                $variant = ProductVariant::find($variantId);
                $newQuantity = max(1, (int)$quantity);

                if ($variant && $variant->stock < $newQuantity) {
                    throw new \RuntimeException("Not enough stock for one of the products.");
                }

                $cart[$variantId]['quantity'] = $newQuantity;
            }
        }

        session()->put('cart.items', $cart);
    }

    /**
     * Remove item from cart
     */
    public function remove(int $variantId): array
    {
        if ($this->isLoggedIn()) {
            $this->removeFromDatabase($variantId);
        } else {
            $this->removeFromSession($variantId);
        }

        return $this->getCartContents();
    }

    /**
     * Remove item from database (for logged in users)
     */
    protected function removeFromDatabase(int $variantId): void
    {
        $customerId = $this->getCustomerId();

        Cart::where('customer_id', $customerId)
            ->where('product_variant_id', $variantId)
            ->delete();
    }

    /**
     * Remove item from session (for guest users)
     */
    protected function removeFromSession(int $variantId): void
    {
        session()->forget("cart.items.{$variantId}");
    }

    /**
     * Get cart items count
     */
    public function count(): int
    {
        if ($this->isLoggedIn()) {
            $customerId = $this->getCustomerId();
            return Cart::where('customer_id', $customerId)->count();
        }

        return count(session()->get('cart.items', []));
    }

    /**
     * Clear all cart items
     */
    public function clear(): void
    {
        if ($this->isLoggedIn()) {
            $customerId = $this->getCustomerId();
            Cart::where('customer_id', $customerId)->delete();
        } else {
            session()->forget('cart.items');
        }

        session()->forget('cart.coupon');
    }
}
