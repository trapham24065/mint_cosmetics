<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders extends Command
{

    /**
     * The name and signature of the console command.
     * Updated to include --force option.
     */
    protected $signature = 'orders:cancel-expired {--force : Force execution regardless of config}';

    /**
     * The console command description.
     */
    protected $description = 'Cancel pending orders that have expired and restore stock';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $isEnabled = config('orders.clean_pending_enabled', false);

        $isForced = $this->option('force');

        if (!$isEnabled && !$isForced) {
            if (app()->isLocal()) {
                $this->info("Order cleaning function is OFF (config: orders.clean_pending_enabled).");
            }
            return;
        }

        $cutOffTime = Carbon::now()->subMinutes(15);

        // Tìm đơn hàng pending quá hạn
        $expiredOrders = Order::where('status', OrderStatus::Pending)
            ->where('created_at', '<', $cutOffTime)
            ->with('orderItems.productVariant') // Eager load để hoàn kho
            ->get();

        $count = $expiredOrders->count();

        if ($count > 0) {
            $this->info("Found {$count} overdue orders. Processing...");

            foreach ($expiredOrders as $order) {
                DB::beginTransaction();
                try {
                    // 1. HOÀN LẠI TỒN KHO (Restore Stock)
                    foreach ($order->orderItems as $item) {
                        if ($item->productVariant) {
                            $item->productVariant->increment('stock', $item->quantity);
                            Log::info(
                                "Restored {$item->quantity} stock for Variant SKU: {$item->productVariant->sku} (Order #{$order->id})"
                            );
                        }
                    }

                    // 2. Cập nhật trạng thái thành Cancelled (Thay vì xóa vĩnh viễn)
                    // Nếu bạn thực sự muốn xóa vĩnh viễn, hãy đổi thành $order->delete();
                    $order->update(['status' => OrderStatus::Cancelled]);

                    // 3. Xóa pending payments (giữ nguyên logic của bạn)
                    DB::table('payments')
                        ->where('order_id', $order->id)
                        ->where('status', '!=', 'success')
                        ->delete();

                    DB::commit();
                    $this->info("Order cancelled & stock restored #{$order->id}");
                    Log::info("Scheduler: Cancelled overdue order #{$order->id}");
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error processing overdue order #{$order->id}: ".$e->getMessage());
                    $this->error("Order processing error #{$order->id}");
                }
            }
            $this->info("Cleanup completed.");
        } elseif ($isForced || app()->isLocal()) {
            $this->info("There are no overdue orders.");
        }
    }

}
