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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{

    /**
     * Show the customer dashboard.
     */
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();

        // Get orders history with relationship
        $orders = $customer->orders()->with('items')->latest()->get();

        return view('storefront.dashboard', compact('customer', 'orders'));
    }

    /**
     * Update customer profile (Name, Email, Password).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:customers,email,'.$customer->id,
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

        return redirect()->route('customer.dashboard')->with('success', 'Account details updated successfully.');
    }

    /**
     * Update customer address (Reuse logic).
     */
    public function updateAddress(UpdateAddressRequest $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        $customer->update($request->validated());

        return redirect()->route('customer.dashboard')->with('success', 'Address updated successfully!');
    }

}
