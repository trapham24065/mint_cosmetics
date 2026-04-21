<?php

/**
 * @project mint_cosmetics
 * @author M397
 * @email m397.dev@gmail.com
 * @date 11/29/2025
 * @time 4:43 PM
 */

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Customer;
use App\Models\OrderReturn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{

    private const ORDER_STATUS_PRIORITY_CASE = "CASE status
        WHEN 'processing' THEN 1
        WHEN 'pending' THEN 2
        WHEN 'shipped' THEN 3
        WHEN 'delivered' THEN 4
        WHEN 'completed' THEN 5
        WHEN 'cancelled' THEN 6
        WHEN 'failed' THEN 7
        ELSE 99
    END";

    private const RETURN_STATUS_PRIORITY_CASE = "CASE status
        WHEN 'pending' THEN 1
        WHEN 'approved' THEN 2
        WHEN 'refunded' THEN 3
        WHEN 'rejected' THEN 4
        WHEN 'cancelled' THEN 5
        ELSE 99
    END";

    /**
     * Show the customer dashboard.
     */
    public function index(): View
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        return view('storefront.customer.dashboard', compact('customer'));
    }

    /**
     * API: Lấy dữ liệu đơn hàng cho tab dashboard (AJAX).
     */
    public function ordersData(): JsonResponse
    {
        $request = request();
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(5, min($perPage, 50));

        $orders = $customer->orders()
            ->orderByRaw(self::ORDER_STATUS_PRIORITY_CASE)
            ->orderByDesc('created_at')
            ->paginate($perPage);
        $html = view('storefront.partials.orders-table', compact('orders'))->render();

        return response()->json([
            'success' => true,
            'data' => [
                'html' => $html,
            ],
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * API: Lấy dữ liệu yêu cầu trả hàng cho tab dashboard (AJAX).
     */
    public function returnsData(): JsonResponse
    {
        $request = request();
        $customerId = Auth::guard('customer')->id();

        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(5, min($perPage, 50));

        $returns = OrderReturn::query()
            ->with('order')
            ->where('customer_id', $customerId)
            ->orderByRaw(self::RETURN_STATUS_PRIORITY_CASE)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $html = view('storefront.partials.returns-table', compact('returns'))->render();

        return response()->json([
            'success' => true,
            'data' => [
                'html' => $html,
            ],
            'meta' => [
                'current_page' => $returns->currentPage(),
                'last_page' => $returns->lastPage(),
                'per_page' => $returns->perPage(),
                'total' => $returns->total(),
            ],
        ]);
    }

    /**
     * Update customer profile (Name, Email, Password).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password'     => 'nullable|string|min:8|confirmed',
        ]);

        // Handle password change
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $customer->password = Hash::make($request->new_password);
        }

        $customer->first_name = $validated['first_name'];
        $customer->last_name = $validated['last_name'];
        $customer->email = $validated['email'];
        $customer->save();

        return redirect()->route('customer.dashboard')->with('success', 'Cập nhật thông tin tài khoản thành công!');
    }

    /**
     * Update customer address (Reuse logic).
     */
    public function updateAddress(UpdateAddressRequest $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = Auth::guard('customer')->user();
        $customer->update($request->validated());

        return redirect()->route('customer.dashboard')->with('success', 'Địa chỉ được cập nhật thành công!');
    }
}
