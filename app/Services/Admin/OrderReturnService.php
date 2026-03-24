<?php

namespace App\Services\Admin;

use App\Enums\ReturnStatus;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderReturnService
{
    /**
     * Tạo yêu cầu trả hàng mới
     */
    public function createReturn(array $data): OrderReturn
    {
        return DB::transaction(function () use ($data) {
            // Tạo order return
            $return = OrderReturn::create([
                'order_id' => $data['order_id'],
                'customer_id' => $data['customer_id'] ?? null,
                'reason' => $data['reason'],
                'description' => $data['description'] ?? null,
                'evidence_images' => $data['evidence_images'] ?? null,
                'status' => ReturnStatus::Pending,
            ]);

            // Tạo return items
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    OrderReturnItem::create([
                        'order_return_id' => $return->id,
                        'order_item_id' => $item['order_item_id'],
                        'quantity' => $item['quantity'],
                        'refund_price' => $item['refund_price'],
                        'item_reason' => $item['item_reason'] ?? null,
                    ]);
                }
            }

            // Tính tổng refund amount
            $return->refund_amount = $return->items->sum(function ($item) {
                return $item->quantity * $item->refund_price;
            });
            $return->save();

            return $return->fresh(['items', 'order', 'customer']);
        });
    }

    /**
     * Duyệt yêu cầu trả hàng
     */
    public function approveReturn(OrderReturn $return, ?string $adminNote = null): bool
    {
        try {
            DB::beginTransaction();

            $return->update([
                'status' => ReturnStatus::Approved,
                'admin_note' => $adminNote,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve return failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Từ chối yêu cầu trả hàng
     */
    public function rejectReturn(OrderReturn $return, string $reason): bool
    {
        try {
            $return->update([
                'status' => ReturnStatus::Rejected,
                'admin_note' => $reason,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Reject return failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hoàn tiền và cộng lại stock
     */
    public function processRefund(OrderReturn $return, array $refundData): bool
    {
        try {
            DB::beginTransaction();

            // Cộng lại stock cho các sản phẩm
            foreach ($return->items as $returnItem) {
                $orderItem = $returnItem->orderItem;

                if ($orderItem->product_variant_id) {
                    $variant = ProductVariant::find($orderItem->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock', $returnItem->quantity);
                    }
                }
            }

            // Cập nhật thông tin hoàn tiền
            $return->update([
                'status' => ReturnStatus::Refunded,
                'refund_method' => $refundData['refund_method'] ?? 'manual',
                'refund_transaction_id' => $refundData['refund_transaction_id'] ?? null,
                'refunded_at' => now(),
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Process refund failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Hủy yêu cầu trả hàng
     */
    public function cancelReturn(OrderReturn $return): bool
    {
        try {
            $return->update([
                'status' => ReturnStatus::Cancelled,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Cancel return failed: ' . $e->getMessage());
            return false;
        }
    }
}
