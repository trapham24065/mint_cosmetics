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
        $title = "Đăng nhập  / Đăng ký";
        return view('storefront.auth.login-register', compact('title'));
    }

    /**
     * Login processing.
     */
    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validate(
            [
                'email'    => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required'    => 'Email không được để trống',
                'email.email'       => 'Email không đúng định dạng',
                'password.required' => 'Mật khẩu không được để trống',
            ]
        );

        $credentials['status'] = 1;

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $this->mergeCartOnLogin();

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Chào mừng trở lại!');
        }

        throw ValidationException::withMessages([
            'email' => __('Thông tin đăng nhập không chính xác hoặc tài khoản bị khóa.'),
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
        ],
            [
                'first_name.required' => 'Tên không được để trống',
                'first_name.max'      => 'Tên không được vượt quá 255 ký tự',

                'last_name.required' => 'Họ không được để trống',
                'last_name.max'      => 'Họ không được vượt quá 255 ký tự',

                'email.required' => 'Email không được để trống',
                'email.email'    => 'Email không đúng định dạng',
                'email.unique'   => 'Email đã tồn tại',
                'email.max'      => 'Email không được vượt quá 255 ký tự',

                'phone.required' => 'Số điện thoại không được để trống',
                'phone.max'      => 'Số điện thoại không được vượt quá 20 ký tự',

                'password.required'  => 'Mật khẩu không được để trống',
                'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp',
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

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công!');
    }

    /**
     * Handling logout.
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Đăng xuất thành công');
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
