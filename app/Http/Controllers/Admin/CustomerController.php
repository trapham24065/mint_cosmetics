<?php
/**
 * @project mint_cosmetics
 * @author M397
 * @email m397.dev@gmail.com
 * @date 12/1/2025
 * @time 10:26 PM
 */

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{

    /**
     * Display a listing of the customers.
     */
    public function index(): View
    {
        $title = 'Customer Management';
        // Get customers with their order count
        $customers = Customer::withCount('orders')->latest()->paginate(15);

        return view('admin.management.customer.index', compact('customers', 'title'));
    }

    public function show(Customer $customer): View
    {
        $title = 'Customer Details';

        $customer->load([
            'orders' => function ($query) {
                $query->latest()->withCount('items');
            },
        ]);

        $totalSpent = $customer->orders()
            ->where('status', OrderStatus::Completed)
            ->sum('total_price');

        return view('admin.management.customer.show', compact('customer', 'title', 'totalSpent'));
    }

    /**
     * Toggle customer status (Active/Blocked).
     */
    public function toggleStatus(Customer $customer): RedirectResponse
    {
        $customer->status = !$customer->status;
        $customer->save();

        $statusMsg = $customer->status ? 'activated' : 'blocked';
        return back()->with('success', "Customer has been {$statusMsg} successfully.");
    }

    /**
     * Delete a customer (Soft delete if trait is used).
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        // Prevent deleting customers with orders (optional business logic)
        if ($customer->orders()->exists()) {
            // Instead of deleting, maybe just block them?
            // For now, let's just block deletion if they have orders for data integrity
            return back()->with('error', 'Cannot delete customer with existing orders. Please block them instead.');
        }

        $customer->delete();
        return back()->with('success', 'Customer deleted successfully.');
    }

    public function getDataForGrid(): JsonResponse
    {
        $customers = Customer::withCount('orders')->latest()->get();

        $data = $customers->map(function ($customer) {
            return [
                'id'           => $customer->id,
                'full_name'    => $customer->full_name,
                'email'        => $customer->email,
                'phone'        => $customer->phone,
                'orders_count' => $customer->orders_count,
                'status'       => $customer->status,
                'created_at'   => $customer->created_at->format('d M, Y'),
            ];
        });

        return response()->json(['data' => $data]);
    }

}
