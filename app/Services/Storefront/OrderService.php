<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/1/2025
 * @time 3:06 PM
 */

namespace App\Services\Storefront;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{

    public function createOrder(array $customerData, array $cartData): Order
    {
        return DB::transaction(function () use ($customerData, $cartData) {
            $coupon = $cartData['coupon'];
            // 1. KIỂM TRA TỒN KHO TRƯỚC (Validation)
            // Lặp qua để đảm bảo tất cả sản phẩm đều đủ hàng TRƯỚC KHI tạo bất cứ thứ gì
            foreach ($cartData['items'] as $item) {
                // Giả sử $item['variant_id'] là ID của biến thể từ CartService
                // lockForUpdate() để ngăn race condition khi nhiều người mua cùng lúc
                $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);

                if (!$variant) {
                    throw new \RuntimeException("The product does not exist.");
                }

                if ($variant->stock < $item['quantity']) {
                    // Lấy tên sản phẩm để thông báo lỗi rõ ràng
                    $productName = $variant->product->name ?? 'Unknown Product';
                    throw new \RuntimeException(
                        "Product '{$productName}' currently out of stock (Remaining stock): {$variant->stock})."
                    );
                }
            }
            // 1. Create a single row main
            $order = Order::create([
                'customer_id'     => $customerData['customer_id'],
                'total_price'     => $cartData['total'],
                'status'          => OrderStatus::Pending,
                'payment_method'  => 'vnpay',
                'first_name'      => $customerData['first_name'],
                'last_name'       => $customerData['last_name'],
                'address'         => $customerData['address'],
                'phone'           => $customerData['phone'],
                'email'           => $customerData['email'],
                'notes'           => $customerData['notes'] ?? null,
                'coupon_code'     => $coupon ? $coupon->code : null,
                'discount_amount' => $cartData['discount'],
            ]);

            foreach ($cartData['items'] as $item) {
                // Chúng ta không dùng DB::table('order_items')->insert() để có thể kích hoạt Model Events nếu cần
                // Nhưng quan trọng hơn là phải trừ tồn kho cho từng item

                $variant = ProductVariant::find($item['variant_id']);

                // Tạo OrderItem
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'product_name'       => $item['product_name'].' ('.($item['variant_name'] ?? '').')',
                    'quantity'           => $item['quantity'],
                    'price'              => $item['price'],
                ]);

                // --- LOGIC QUAN TRỌNG: Trừ tồn kho ---
                $variant->decrement('stock', $item['quantity']);
            }

            // 4. Cập nhật số lần sử dụng Coupon (nếu có)
            if ($coupon) {
                $coupon->increment('times_used'); // Giả sử model Coupon có cột times_used
            }

            return $order;
        });
    }

}
