<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminPendingEmailVerificationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EmailChangeController extends Controller
{
    /**
     * Number of hours the pending email confirmation link is valid.
     */
    public const HOURS_VALID = 24;

    /**
     * Public endpoint hit when an admin clicks the confirmation link in their email.
     * Swaps pending_email into the email column if the token is still valid.
     */
    public function confirm(string $token): RedirectResponse
    {
        $user = User::where('pending_email_token', $token)->first();

        if (! $user || ! $user->hasPendingEmailChange()) {
            return redirect()->route('admin.login')
                ->with('error', 'Liên kết xác nhận không hợp lệ hoặc đã được sử dụng.');
        }

        if ($user->isPendingEmailExpired(self::HOURS_VALID)) {
            $user->clearPendingEmailChange();

            return redirect()->route('admin.login')
                ->with('error', 'Liên kết xác nhận đã hết hạn. Vui lòng đăng nhập và gửi lại yêu cầu.');
        }

        $newEmail = $user->pending_email;

        if (User::where('email', $newEmail)->where('id', '!=', $user->id)->exists()) {
            $user->clearPendingEmailChange();

            return redirect()->route('admin.login')
                ->with('error', 'Địa chỉ email này hiện đã được sử dụng bởi tài khoản khác.');
        }

        $user->forceFill([
            'email'                 => $newEmail,
            'email_verified_at'     => now(),
            'pending_email'         => null,
            'pending_email_token'   => null,
            'pending_email_sent_at' => null,
        ])->save();

        return redirect()->route('admin.login')
            ->with('success', 'Đã xác nhận email mới thành công. Vui lòng đăng nhập lại bằng địa chỉ email mới.');
    }

    /**
     * Cancel a pending email change for the currently authenticated admin.
     */
    public function cancel(): RedirectResponse
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (! $admin->hasPendingEmailChange()) {
            return back()->with('info', 'Không có yêu cầu đổi email nào đang chờ xử lý.');
        }

        $admin->clearPendingEmailChange();

        return back()->with('success', 'Đã huỷ yêu cầu thay đổi email.');
    }

    /**
     * Resend the confirmation email to the pending email address.
     */
    public function resend(): RedirectResponse
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (! $admin->hasPendingEmailChange()) {
            return back()->with('error', 'Không có yêu cầu đổi email nào đang chờ xử lý.');
        }

        $admin->forceFill(['pending_email_sent_at' => now()])->save();

        $admin->notify(new AdminPendingEmailVerificationNotification(
            $admin->pending_email,
            $admin->pending_email_token,
            self::HOURS_VALID
        ));

        return back()->with('success', 'Đã gửi lại email xác nhận tới địa chỉ ' . $admin->pending_email . '.');
    }
}
