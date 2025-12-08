<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/1/2025
 * @time 3:06 PM
 */

namespace App\Services\Storefront;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function createOrder(array $customerData, array $cartData): Order
    {
        return DB::transaction(function () use ($customerData, $cartData) {
            $coupon = $cartData['coupon'];

            // 1. Create a single row main
            $order = Order::create([
                'customer_id'     => $customerData['customer_id'],
                'total_price'     => $cartData['total'],
                'status'          => 'pending',
                'first_name'      => $customerData['first_name'],
                'last_name'       => $customerData['last_name'],
                'address'         => $customerData['address'],
                'phone'           => $customerData['phone'],
                'email'           => $customerData['email'],
                'notes'           => $customerData['notes'] ?? null,
                'coupon_code'     => $coupon ? $coupon->code : null,
                'discount_amount' => $cartData['discount'],
            ]);

            // 2. Create order items from cart
            $orderItems = [];
            foreach ($cartData['items'] as $item) {
                $orderItems[] = [
                    'order_id'           => $order->id,
                    'product_id'         => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'product_name'       => $item['product_name'].' ('.$item['variant_name'].')',
                    'quantity'           => $item['quantity'],
                    'price'              => $item['price'],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
            DB::table('order_items')->insert($orderItems);
            if ($coupon) {
                $coupon->increment('times_used');
            }
            return $order;
        });
    }

}
