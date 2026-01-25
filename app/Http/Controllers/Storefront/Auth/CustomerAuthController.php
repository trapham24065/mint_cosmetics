<?php

namespace App\Http\Controllers\Storefront\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{

    /**
     * Display login and registration form.
     */
    public function showLoginForm()
    {
        $title = "Login / Register";
        return view('storefront.auth.login-register', compact('title'));
    }

    /**
     * Login processing.
     */
    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials['status'] = 1;

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $this->mergeCartOnLogin();

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Welcome back!');
        }

        throw ValidationException::withMessages([
            'email' => __('Incorrect login information or locked account.'),
        ]);
    }

    /**
     * Registration processing.
     */
    public function register(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:customers',
            'phone'      => 'required|string|max:20',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'password'   => Hash::make($request->password),
            'status'     => true,
        ]);

        Auth::guard('customer')->login($customer);

        $this->mergeCartOnLogin();

        return redirect()->route('home')->with('success', 'Account registration successful!');
    }

    /**
     * Handling logout.
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Logic to merge shopping cart from Session to Database after login.
     */
    protected function mergeCartOnLogin(): void
    {
        $sessionCart = Session::get('cart.items', []);

        if (!empty($sessionCart)) {
            $customerId = Auth::guard('customer')->id();

            foreach ($sessionCart as $variantId => $details) {
                $cartItem = Cart::where('customer_id', $customerId)
                    ->where('product_variant_id', $variantId)
                    ->first();

                if ($cartItem) {
                    // Merge quantities: add session quantity to existing DB quantity
                    $cartItem->quantity += $details['quantity'];
                    $cartItem->save();
                } else {
                    // Create new cart item in database
                    Cart::create([
                        'customer_id'        => $customerId,
                        'product_variant_id' => $variantId,
                        'quantity'           => $details['quantity'],
                    ]);
                }
            }

            // Clear session cart after merging
            Session::forget('cart.items');
        }
    }
}
