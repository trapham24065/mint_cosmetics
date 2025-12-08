<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{

    /**
     * Hiển thị form nhập email để yêu cầu link reset.
     */
    public function showLinkRequestForm(): View
    {
        return view('storefront.auth.passwords.email');
    }

    /**
     * Xử lý gửi link reset mật khẩu qua email.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        // Gửi link reset password sử dụng broker 'customers'
        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Hiển thị form nhập mật khẩu mới (khi click link từ email).
     */
    public function showResetForm(Request $request, $token = null): View
    {
        return view('storefront.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Xử lý đặt lại mật khẩu mới.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Cố gắng reset password sử dụng broker 'customers'
        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('customer.login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Reset the given user's password.
     */
    protected function resetPassword($user, $password): void
    {
        $user->password = Hash::make($password);

        // Nếu bạn dùng cột remember_token
        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        // Tùy chọn: Đăng nhập ngay sau khi reset
        // Auth::guard('customer')->login($user);
    }

}
