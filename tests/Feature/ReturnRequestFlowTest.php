<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReturnRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_return_request_for_delivered_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create([
            'status' => 'delivered',
            'delivered_at' => now()->subDays(2),
        ]);

        $this->actingAs($user)
            ->post(route('customer.returns.store', $order), [
                'reason' => 'damaged',
                'details' => 'Sản phẩm bị nứt vỡ.',
            ])
            ->assertRedirect(route('customer.returns.index'));

        $this->assertDatabaseHas('return_requests', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'reason' => 'damaged',
            'status' => 'pending',
        ]);
    }

    public function test_customer_cannot_create_return_request_out_of_return_window(): void
    {
        config(['orders.return_days' => 7]);

        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create([
            'status' => 'delivered',
            'delivered_at' => now()->subDays(15),
        ]);

        $this->actingAs($user)
            ->from(route('customer.returns.create', $order))
            ->post(route('customer.returns.store', $order), [
                'reason' => 'damaged',
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('return_requests', [
            'user_id' => $user->id,
            'order_id' => $order->id,
        ]);
    }

    public function test_admin_can_approve_return_request(): void
    {
        $this->withoutMiddleware();

        $customer = User::factory()->create();
        $admin = User::factory()->create();

        $order = Order::factory()->for($customer)->create([
            'status' => 'delivered',
            'delivered_at' => now()->subDays(1),
        ]);

        $return = ReturnRequest::create([
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'reason' => 'wrong_item',
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.returns.updateStatus', $return), [
                'status' => 'approved',
                'admin_note' => 'Đã duyệt, vui lòng gửi hàng về kho.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('return_requests', [
            'id' => $return->id,
            'status' => 'approved',
        ]);
    }
}