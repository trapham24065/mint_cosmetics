<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Order;

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
        $expirationTime = 15; // Set expiration time to 15 minutes

        $expiredOrders = Order::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subMinutes($expirationTime))
            ->get();

        foreach ($expiredOrders as $order) {
            $order->status = 'cancelled';
            $order->save();
            $this->info("Order #{$order->id} has been cancelled.");
        }

        $this->info('Checked for expired orders successfully.');
        return 0;
    }

}
