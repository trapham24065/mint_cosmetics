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
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending orders that have expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isEnabled = config('app.clean_pending_orders_enabled', env('CLEAN_PENDING_ORDERS_ENABLED', false));

        $isForced = $this->option('force');

        if (!$isEnabled && !$isForced) {
            if (app()->isLocal()) {
                $this->info("Order cleaning function is OFF (CLEAN_PENDING_ORDERS_ENABLED=false).");
            }
            return;
        }

        $cutOffTime = Carbon::now()->subMinutes(15);

        $expiredOrders = Order::where('status', OrderStatus::Pending)
            ->where('created_at', '<', $cutOffTime)
            ->with('orderItems.productVariant')
            ->get();

        $count = $expiredOrders->count();

        if ($count > 0) {
            $this->info("Found {$count} overdue orders. Processing...");

            foreach ($expiredOrders as $order) {
                DB::beginTransaction();
                try {
                    // 1. Return to stock (if required)
                    // ...

                    // 2. Delete order
                    $order->delete();

                    // 3. Delete pending payments
                    DB::table('payments')
                        ->where('order_id', $order->id)
                        ->where('status', '!=', 'success')
                        ->delete();

                    DB::commit();
                    $this->info("Order deleted #{$order->id}");
                    Log::info("Scheduler: Deleted overdue orders #{$order->id}");
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error when deleting overdue order #{$order->id}: ".$e->getMessage());
                    $this->error("Order processing error #{$order->id}");
                }
            }
            $this->info("Cleanup completed.");
        } else {
            // Only show this message if running manually to avoid confusion
            if ($isForced || app()->isLocal()) {
                $this->info("There are no overdue orders..");
            }
        }
    }

}
