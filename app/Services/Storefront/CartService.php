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

use App\Models\ProductVariant;

class CartService
{

    public function add(int $variantId, int $quantity): array
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        if ($variant->stock < $quantity) {
            throw new \RuntimeException('Product is out of stock.');
        }

        $cart = session()->get('cart', []);
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

        session()->put('cart', $cart);

        return [
            'success'   => true,
            'cartCount' => $this->count(),
            'addedItem' => $cart[$cartItemId],
        ];
    }

    public function getCartContents(): array
    {
        $cartItems = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // You can add shipping logic here later
        $total = $subtotal;

        return [
            'items'    => $cartItems,
            'subtotal' => $subtotal,
            'total'    => $total,
        ];
    }

    public function updateQuantities(array $updates): array
    {
        $cart = session()->get('cart', []);
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
        session()->put('cart', $cart);
        return $this->getCartContents();
    }

    public function remove(int $variantId): array
    {
        session()->forget("cart.{$variantId}");
        return $this->getCartContents();
    }

    public function count(): int
    {
        return count(session()->get('cart', []));
    }

}
